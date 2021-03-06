<?php
require_once "GameLogic.php";
require_once "controller/Controller.php";

class Spin extends Controller
{
    private $gameSoundUrl;
    private $GameLogic;

    function __construct()
    {
        parent::__construct();
        $this->GameLogic = new GameLogic;
        $this->gameSoundUrl = $this->GameLogic->gameSoundUrl;
        return;
    }

    public function handle($request)
    {
        $sessid = $request['sessid'];
        $betDenomination = $request['bet_denomination'];
        $betBetLevel = $request['bet_betlevel'];
        $gameId = $request['gameId'];

        $user = $this->User->getBySession($sessid);
        if (!$betDenomination || !$betBetLevel || $user === FALSE || !$gameId) {
            $this->Log->e($request['action'] . ' bet_denomination or bet_betlevel or user or gameId is false');
            return print($this->Error->sendError(0));
        }
        $betPrice = $this->MoneyManager->setBetLevel($betBetLevel, $betDenomination)['betPrice'];

        $transactionsInit = $this->Transaction->getUserTransactionsInit($sessid, $gameId)['calculBigWin'];

        $diffBalanceWin = $transactionsInit - $user['balance'];

        $bigWin = $this->GameLogic->bigWin * ($betDenomination * 2) * $betBetLevel;
        $megaWin = $this->GameLogic->megaWin * ($betDenomination * 2) * $betBetLevel;
        $bigWinResult = ($bigWin < $diffBalanceWin) ? $this->GameLogic->winPercent() : false;

        $priceWin = $this->priceWin($sessid, $bigWinResult, $bigWin, $megaWin, $betPrice, $diffBalanceWin, $user['balance'], $gameId);
        $minWin = $priceWin['min'];
        $maxWin = $priceWin['max'];

        $linkedReels = '';
        $weights = '';

        for ($i = -1; 1 > $i; $i--) {
            $linkedReels = $this->GameLogic->linkedReels();
            $weights = $this->GameLogic->renderWeights($linkedReels);
            $checkCountMoney = $this->GameLogic->checkCountMoney($minWin, $maxWin, $weights, $betDenomination, $betBetLevel, $betPrice, $bigWinResult, $i);
            if ($checkCountMoney == true) {
                break;
            }
        }
        $renderWin = $this->GameLogic->renderWin($weights);
        $positionsWin = $this->GameLogic->positionsWin($renderWin);
        $winCoins = $this->GameLogic->winCoins($renderWin, $betDenomination, $betBetLevel);

        $winMoney = ($winCoins['totalwin.cent'] / 100);
        $changeBalanceUser = $this->MoneyManager->changeBalance(($user['balance'] + $winMoney), $betBetLevel, $betDenomination);
        $balanceUser = $changeBalanceUser['balance'];
        $balanceCentsUser = $changeBalanceUser['balanceCents'];
        $balanceCoinsUser = $changeBalanceUser['balanceCoins'];

        $credit = $this->MoneyManager->convertBalance($balanceUser);

        if ($bigWinResult == true) {
            $this->Transaction->updateUserTransactionsInit($sessid, ($balanceUser - $winMoney), $gameId);
        }
        $this->User->save($user['uid'], $balanceUser);

        $spinArray = [
            'credit' => $credit['cents'],
            'clientaction' => $request['action'],
            'gamesoundurl' => $this->gameSoundUrl,
            'linkedreels' => $linkedReels,
            'rs.i0.r.i0.syms' => implode(',', $weights[0]),
            'rs.i0.r.i1.syms' => implode(',', $weights[1]),
            'rs.i0.r.i2.syms' => implode(',', $weights[2]),
            'rs.i0.r.i3.syms' => implode(',', $weights[3]),
            'rs.i0.r.i4.syms' => implode(',', $weights[4]),

            'rs.i0.id' => 'basic',
            'rs.i0.r.i0.id' => 'basic-0',
            'rs.i0.r.i1.id' => 'basic-1',
            'rs.i0.r.i2.id' => 'twinreel-2-3',
            'rs.i0.r.i3.id' => 'twinreel-2-3',
            'rs.i0.r.i4.id' => 'basic-4',

            'rs.i0.r.i0.hold' => 'false',
            'rs.i0.r.i1.hold' => 'false',
            'rs.i0.r.i2.hold' => 'false',
            'rs.i0.r.i3.hold' => 'false',
            'rs.i0.r.i4.hold' => 'false',

            'historybutton' => 'false',
            'multiplier' => '1',
            'nextaction' => 'spin',
            'isJackpotWin' => 'false',
            'jackpotcurrencyiso' => 'EUR',
            'jackpotcurrency' => '€',
            'playforfun' => 'true',
            'playercurrency' => '€',
            'playercurrencyiso' => 'EUR',
            'g4mode' => 'false',
            'gamestate.history' => 'basic',
            'gamestate.current' => 'basic',
            'gameover' => 'true',
            'gamestate.stack' => 'basic',
            'wavecount' => '1',
        ];

        $response = array_merge($positionsWin, $spinArray, $winCoins);

        if ($this->Transaction->saveTransaction($gameId, $user['uid'], $sessid, $request['action'], $betPrice, $betBetLevel, $balanceCentsUser, $balanceCoinsUser, $betDenomination, json_encode($weights), $winCoins['totalwin.cent'], $winCoins['totalwin.coins'], $balanceUser) === FALSE) {
            return print($this->Error->sendError(0));
        }
        return print(urldecode(http_build_query($response)));
    }

    private function priceWin($sessid, $bigWinResult, $bigWin, $megaWin, $betPrice, $diffBalanceWin, $userBalance, $gameId)
    {
        $balance5game = $this->balance5game($sessid, $gameId);
        $balanceDiffPercentage = (($balance5game - $userBalance) / $balance5game) * 100;

        $factor = ($balanceDiffPercentage > 15) ? 1.5 : 1;
        if ($bigWinResult == true) {
            $priceWin['min'] = $bigWin;
            $priceWin['max'] = (($megaWin - $betPrice) > $diffBalanceWin) ? $diffBalanceWin : ($megaWin - $betPrice);
        } else {
            $priceWin['min'] = ($betPrice / 2) * $factor;
            $priceWin['max'] = ($betPrice * 2.5) * $factor;
        }
        return $priceWin;
    }

    private function balance5game($sessid, $gameId)
    {
        $balance5game = 0;
        $transactions = $this->Transaction->getUserTransactionsSpin($sessid, $gameId);
        foreach ($transactions as $item) {
            $balance5game += $item['balance'];
        }
        $countGame = count($transactions);
        return $balance5game / $countGame;
    }
}
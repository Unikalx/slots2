
<?php
require_once "models/logger.php";
require_once "models/moneyManager.php";
require_once "models/sql.php";
require_once "models/model.php";

class Spin
{
    private $gameSoundUrl;

    private $sql;
    private $log;
    private $model;
    private $moneyManager;


    function __construct()
    {
        $this->gameSoundUrl = $this->model->gameSoundUrl;

        $this->log = new Logger;
        $this->model = new Model;
        $this->sql = new Sql;
        $this->moneyManager = new MoneyManager;
        return;
    }

    public function spin($request)
    {
        $sessid = $request['sessid'];
        $betDenomination = $request['bet_denomination'];
        $betBetLevel = $request['bet_betlevel'];

        $user = $this->sql->getUserData($sessid);
        if (!$betDenomination || !$betBetLevel || $user === FALSE) {
            $this->log->e($request['action'] . ' bet_denomination or bet_betlevel or user is false');
            die();
        }
        $betPrice = $this->moneyManager->betLevels($betBetLevel, $betDenomination);

        $transactionsInit = $this->sql->getUserTransactionsInit($sessid)['calculBigWin'];
        $diffBalanceWin = $transactionsInit - $user['balance'];

        $bigWin = $this->model->bigWin * ($betDenomination * 2) * $betBetLevel;
        $megaWin = $this->model->megaWin * ($betDenomination * 2) * $betBetLevel;
        $bigWinResult = ($bigWin < $diffBalanceWin) ? $this->model->winPercent() : false;

        $priceWin = $this->priceWin($sessid, $bigWinResult, $bigWin, $megaWin, $betPrice, $diffBalanceWin, $user['balance']);
        $minWin = $priceWin['min'];
        $maxWin = $priceWin['max'];

        $linkedReels = '';
        $weights = '';

        for ($i = -1; 1 > $i; $i--) {
            $linkedReels = $this->model->linkedReels();
            $weights = $this->model->renderWeights($linkedReels);
            $checkCountMoney = $this->model->checkCountMoney($minWin, $maxWin, $weights, $betDenomination, $betBetLevel, $betPrice, $bigWinResult, $i);
            if ($checkCountMoney == true) {
                break;
            }
        }
        $renderWin = $this->model->renderWin($weights);
        $positionsWin = $this->model->positionsWin($renderWin);
        $winCoins = $this->model->winCoins($renderWin, $betDenomination, $betBetLevel);
        $winMoney = ($winCoins['totalwin.cent'] / 100);
        $balanceUser = $user['balance'] + $winMoney - $betPrice;


        $credit = $this->moneyManager->convertBalance($balanceUser);
        if ($bigWinResult == true) {
            $this->sql->updateUserTransactionsInit($sessid, ($transactionsInit - $winMoney));
        }
        $this->sql->userSave($user, $balanceUser);

        $spinArray = [
            'credit' => $credit['cash'],
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

//            'rs.i0.r.i0.pos' => '44',
//            'rs.i0.r.i1.pos' => '11',
//            'rs.i0.r.i2.pos' => '54',
//            'rs.i0.r.i3.pos' => '54',
//            'rs.i0.r.i4.pos' => '38',

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

        $saveTransaction = [
            'uid' => $user['uid'],
            'sessid' => $sessid,
            'action' => $request['action'],
            'bet' => $betPrice,
            'betlevel' => $betBetLevel,
            'playerBalanceCents' => ($balanceUser * 100),
            'playerBalanceCoins' => $this->moneyManager->convertBalance($balanceUser, $betDenomination)['coins'],
            'denomination' => $betDenomination,
            'preCombination' => json_encode($weights),
            'totalWinCents' => $winCoins['totalwin.cent'],
            'totalWinCoins' => $winCoins['totalwin.coins'],
            'balance' => $balanceUser,
            'calculBigWin' => 0
        ];

        if ($this->sql->saveTransaction($saveTransaction) === FALSE) {
            $this->log->e($request['action'] . ' transaction was not saved');
            die();
        }
        return print(urldecode(http_build_query($response)));
    }

    private function priceWin($sessid, $bigWinResult, $bigWin, $megaWin, $betPrice, $diffBalanceWin, $userBalance)
    {
        $balance5game = $this->balance5game($sessid);
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

    private function balance5game($sessid)
    {
        $balance5game = 0;
        $transactions = $this->sql->getUserTransactionsSpin($sessid);
        foreach ($transactions as $item) {
            $balance5game += $item['balance'];
        }
        $countGame = count($transactions);
        return $balance5game / $countGame;
    }
}
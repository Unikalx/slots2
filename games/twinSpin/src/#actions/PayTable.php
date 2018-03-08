<?php
require_once "models/logger.php";
require_once "models/moneyManager.php";
require_once "models/sql.php";
require_once "models/model.php";


class PayTable
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

    public function payTable($request)
    {
        $user = $this->sql->getUserData($request['sessid']);

        if ($user === FALSE) {
            $this->log->e('Your session is ended, open the game window again (actions php/paytable)');
            die();
        }
        $convertedBalance = $this->moneyManager->convertBalance($user['balance']);
        $payTableResponse = [
            'credit' => $convertedBalance['cash'],
            'gamesoundurl' => $this->gameSoundUrl,
            'pt.i0.id' => 'basic',
            'pt.i0.comp.i0.n' => '3',
            'pt.i0.comp.i1.n' => '4',
            'pt.i0.comp.i2.n' => '5',
            'pt.i0.comp.i3.n' => '3',
            'pt.i0.comp.i4.n' => '4',
            'pt.i0.comp.i5.n' => '5',
            'pt.i0.comp.i6.n' => '3',
            'pt.i0.comp.i7.n' => '4',
            'pt.i0.comp.i8.n' => '5',
            'pt.i0.comp.i9.n' => '3',
            'pt.i0.comp.i10.n' => '4',
            'pt.i0.comp.i11.n' => '5',
            'pt.i0.comp.i12.n' => '3',
            'pt.i0.comp.i13.n' => '4',
            'pt.i0.comp.i14.n' => '5',
            'pt.i0.comp.i15.n' => '3',
            'pt.i0.comp.i16.n' => '4',
            'pt.i0.comp.i17.n' => '5',
            'pt.i0.comp.i18.n' => '3',
            'pt.i0.comp.i19.n' => '4',
            'pt.i0.comp.i20.n' => '5',
            'pt.i0.comp.i21.n' => '3',
            'pt.i0.comp.i22.n' => '4',
            'pt.i0.comp.i23.n' => '5',
            'pt.i0.comp.i24.n' => '3',
            'pt.i0.comp.i25.n' => '4',
            'pt.i0.comp.i26.n' => '5',
            'pt.i0.comp.i27.n' => '3',
            'pt.i0.comp.i28.n' => '4',
            'pt.i0.comp.i29.n' => '5',
            'pt.i0.comp.i30.n' => '3',
            'pt.i0.comp.i31.n' => '4',
            'pt.i0.comp.i32.n' => '5',

            'pt.i0.comp.i0.multi' => '50',
            'pt.i0.comp.i1.multi' => '250',
            'pt.i0.comp.i2.multi' => '1000',
            'pt.i0.comp.i3.multi' => '30',
            'pt.i0.comp.i4.multi' => '150',
            'pt.i0.comp.i5.multi' => '500',
            'pt.i0.comp.i6.multi' => '15',
            'pt.i0.comp.i7.multi' => '100',
            'pt.i0.comp.i8.multi' => '400',
            'pt.i0.comp.i9.multi' => '10',
            'pt.i0.comp.i10.multi' => '75',
            'pt.i0.comp.i11.multi' => '250',
            'pt.i0.comp.i12.multi' => '10',
            'pt.i0.comp.i13.multi' => '75',
            'pt.i0.comp.i14.multi' => '250',
            'pt.i0.comp.i15.multi' => '4',
            'pt.i0.comp.i16.multi' => '15',
            'pt.i0.comp.i17.multi' => '40',
            'pt.i0.comp.i18.multi' => '4',
            'pt.i0.comp.i19.multi' => '15',
            'pt.i0.comp.i20.multi' => '40',
            'pt.i0.comp.i21.multi' => '4',
            'pt.i0.comp.i22.multi' => '15',
            'pt.i0.comp.i23.multi' => '40',
            'pt.i0.comp.i24.multi' => '3',
            'pt.i0.comp.i25.multi' => '10',
            'pt.i0.comp.i26.multi' => '25',
            'pt.i0.comp.i27.multi' => '3',
            'pt.i0.comp.i28.multi' => '10',
            'pt.i0.comp.i29.multi' => '25',
            'pt.i0.comp.i30.multi' => '3',
            'pt.i0.comp.i31.multi' => '10',
            'pt.i0.comp.i32.multi' => '25',

            'pt.i0.comp.i0.symbol' => 'SYM3',
            'pt.i0.comp.i1.symbol' => 'SYM3',
            'pt.i0.comp.i2.symbol' => 'SYM3',
            'pt.i0.comp.i3.symbol' => 'SYM4',
            'pt.i0.comp.i4.symbol' => 'SYM4',
            'pt.i0.comp.i5.symbol' => 'SYM4',
            'pt.i0.comp.i6.symbol' => 'SYM5',
            'pt.i0.comp.i7.symbol' => 'SYM5',
            'pt.i0.comp.i8.symbol' => 'SYM5',
            'pt.i0.comp.i9.symbol' => 'SYM6',
            'pt.i0.comp.i10.symbol' => 'SYM6',
            'pt.i0.comp.i11.symbol' => 'SYM6',
            'pt.i0.comp.i12.symbol' => 'SYM7',
            'pt.i0.comp.i13.symbol' => 'SYM7',
            'pt.i0.comp.i14.symbol' => 'SYM7',
            'pt.i0.comp.i15.symbol' => 'SYM8',
            'pt.i0.comp.i16.symbol' => 'SYM8',
            'pt.i0.comp.i17.symbol' => 'SYM8',
            'pt.i0.comp.i18.symbol' => 'SYM9',
            'pt.i0.comp.i19.symbol' => 'SYM9',
            'pt.i0.comp.i20.symbol' => 'SYM9',
            'pt.i0.comp.i21.symbol' => 'SYM10',
            'pt.i0.comp.i22.symbol' => 'SYM10',
            'pt.i0.comp.i23.symbol' => 'SYM10',
            'pt.i0.comp.i24.symbol' => 'SYM11',
            'pt.i0.comp.i25.symbol' => 'SYM11',
            'pt.i0.comp.i26.symbol' => 'SYM11',
            'pt.i0.comp.i27.symbol' => 'SYM12',
            'pt.i0.comp.i28.symbol' => 'SYM12',
            'pt.i0.comp.i29.symbol' => 'SYM12',
            'pt.i0.comp.i30.symbol' => 'SYM13',
            'pt.i0.comp.i31.symbol' => 'SYM13',
            'pt.i0.comp.i32.symbol' => 'SYM13',

            'pt.i0.comp.i0.type' => 'betline',
            'pt.i0.comp.i1.type' => 'betline',
            'pt.i0.comp.i2.type' => 'betline',
            'pt.i0.comp.i3.type' => 'betline',
            'pt.i0.comp.i4.type' => 'betline',
            'pt.i0.comp.i5.type' => 'betline',
            'pt.i0.comp.i6.type' => 'betline',
            'pt.i0.comp.i7.type' => 'betline',
            'pt.i0.comp.i8.type' => 'betline',
            'pt.i0.comp.i9.type' => 'betline',
            'pt.i0.comp.i10.type' => 'betline',
            'pt.i0.comp.i11.type' => 'betline',
            'pt.i0.comp.i12.type' => 'betline',
            'pt.i0.comp.i13.type' => 'betline',
            'pt.i0.comp.i14.type' => 'betline',
            'pt.i0.comp.i15.type' => 'betline',
            'pt.i0.comp.i16.type' => 'betline',
            'pt.i0.comp.i17.type' => 'betline',
            'pt.i0.comp.i18.type' => 'betline',
            'pt.i0.comp.i19.type' => 'betline',
            'pt.i0.comp.i20.type' => 'betline',
            'pt.i0.comp.i21.type' => 'betline',
            'pt.i0.comp.i22.type' => 'betline',
            'pt.i0.comp.i23.type' => 'betline',
            'pt.i0.comp.i24.type' => 'betline',
            'pt.i0.comp.i25.type' => 'betline',
            'pt.i0.comp.i26.type' => 'betline',
            'pt.i0.comp.i27.type' => 'betline',
            'pt.i0.comp.i28.type' => 'betline',
            'pt.i0.comp.i29.type' => 'betline',
            'pt.i0.comp.i30.type' => 'betline',
            'pt.i0.comp.i31.type' => 'betline',
            'pt.i0.comp.i32.type' => 'betline',

            'pt.i0.comp.i0.freespins' => '0',
            'pt.i0.comp.i1.freespins' => '0',
            'pt.i0.comp.i2.freespins' => '0',
            'pt.i0.comp.i3.freespins' => '0',
            'pt.i0.comp.i4.freespins' => '0',
            'pt.i0.comp.i5.freespins' => '0',
            'pt.i0.comp.i6.freespins' => '0',
            'pt.i0.comp.i7.freespins' => '0',
            'pt.i0.comp.i8.freespins' => '0',
            'pt.i0.comp.i9.freespins' => '0',
            'pt.i0.comp.i10.freespins' => '0',
            'pt.i0.comp.i11.freespins' => '0',
            'pt.i0.comp.i12.freespins' => '0',
            'pt.i0.comp.i13.freespins' => '0',
            'pt.i0.comp.i14.freespins' => '0',
            'pt.i0.comp.i15.freespins' => '0',
            'pt.i0.comp.i16.freespins' => '0',
            'pt.i0.comp.i17.freespins' => '0',
            'pt.i0.comp.i18.freespins' => '0',
            'pt.i0.comp.i19.freespins' => '0',
            'pt.i0.comp.i20.freespins' => '0',
            'pt.i0.comp.i21.freespins' => '0',
            'pt.i0.comp.i22.freespins' => '0',
            'pt.i0.comp.i23.freespins' => '0',
            'pt.i0.comp.i24.freespins' => '0',
            'pt.i0.comp.i25.freespins' => '0',
            'pt.i0.comp.i26.freespins' => '0',
            'pt.i0.comp.i27.freespins' => '0',
            'pt.i0.comp.i28.freespins' => '0',
            'pt.i0.comp.i29.freespins' => '0',
            'pt.i0.comp.i30.freespins' => '0',
            'pt.i0.comp.i31.freespins' => '0',
            'pt.i0.comp.i32.freespins' => '0',

            'bl.i0.id' => '0',
            'bl.i0.coins' => '25',
            'bl.i0.line' => '0/1/2,0/1/2,0/1/2,0/1/2,0/1/2',
            'bl.i0.reelset' => 'ALL',

            'g4mode' => 'false',
            'isJackpotWin' => 'false',
            'clientaction' => 'paytable',
            'playercurrencyiso' => 'EUR',
            'jackpotcurrencyiso' => 'EUR',
            'jackpotcurrency' => '€',
            'playercurrency' => '€',
            'historybutton' => 'false',
            'playforfun' => 'true',
        ];
//        return print('pt.i0.comp.i22.n=4&pt.i0.comp.i19.type=betline&pt.i0.comp.i31.multi=10&pt.i0.comp.i32.n=5&pt.i0.comp.i5.symbol=SYM4&pt.i0.comp.i25.freespins=0&pt.i0.comp.i9.multi=10&pt.i0.comp.i4.symbol=SYM4&pt.i0.comp.i29.type=betline&pt.i0.comp.i16.freespins=0&pt.i0.comp.i20.n=5&pt.i0.comp.i28.type=betline&pt.i0.comp.i9.n=3&pt.i0.comp.i24.n=3&pt.i0.comp.i12.multi=10&pt.i0.comp.i21.freespins=0&pt.i0.comp.i30.freespins=0&pt.i0.comp.i3.type=betline&pt.i0.comp.i8.type=betline&pt.i0.comp.i29.n=5&pt.i0.comp.i14.type=betline&pt.i0.comp.i20.freespins=0&pt.i0.comp.i6.symbol=SYM5&pt.i0.comp.i3.multi=30&pt.i0.comp.i21.multi=4&pt.i0.comp.i0.type=betline&pt.i0.comp.i0.symbol=SYM3&pt.i0.comp.i15.type=betline&pt.i0.comp.i8.symbol=SYM5&pt.i0.comp.i15.symbol=SYM8&pt.i0.comp.i14.multi=250&clientaction=paytable&pt.i0.comp.i10.multi=75&pt.i0.comp.i7.type=betline&pt.i0.comp.i9.freespins=0&pt.i0.comp.i2.symbol=SYM3&pt.i0.comp.i29.symbol=SYM12&playercurrencyiso=EUR&historybutton=false&pt.i0.comp.i17.freespins=0&pt.i0.comp.i10.freespins=0&pt.i0.comp.i25.multi=10&pt.i0.comp.i8.freespins=0&pt.i0.comp.i27.freespins=0&pt.i0.comp.i19.freespins=0&pt.i0.comp.i32.freespins=0&pt.i0.comp.i30.n=3&pt.i0.comp.i22.symbol=SYM10&gamesoundurl=https%3A%2F%2Fstatic.casinomodule.com&pt.i0.comp.i6.freespins=0&pt.i0.comp.i8.n=5&pt.i0.comp.i32.type=betline&pt.i0.comp.i19.n=4&pt.i0.comp.i30.symbol=SYM13&pt.i0.comp.i6.n=3&pt.i0.comp.i22.freespins=0&pt.i0.comp.i4.type=betline&pt.i0.comp.i5.n=5&pt.i0.comp.i7.n=4&pt.i0.comp.i0.freespins=0&pt.i0.comp.i5.type=betline&pt.i0.comp.i5.multi=500&pt.i0.comp.i2.n=5&pt.i0.comp.i17.symbol=SYM8&pt.i0.comp.i0.multi=50&pt.i0.comp.i11.n=5&pt.i0.comp.i13.symbol=SYM7&pt.i0.comp.i12.n=3&pt.i0.comp.i9.symbol=SYM6&pt.i0.comp.i23.multi=40&pt.i0.comp.i1.symbol=SYM3&pt.i0.comp.i17.n=5&pt.i0.comp.i1.n=4&pt.i0.comp.i10.n=4&pt.i0.comp.i7.freespins=0&pt.i0.comp.i3.n=3&pt.i0.comp.i20.type=betline&pt.i0.comp.i15.multi=4&pt.i0.comp.i32.multi=25&pt.i0.comp.i27.n=3&pt.i0.comp.i13.type=betline&pt.i0.comp.i4.n=4&pt.i0.comp.i32.symbol=SYM13&pt.i0.comp.i3.symbol=SYM4&pt.i0.comp.i25.n=4&pt.i0.comp.i11.type=betline&pt.i0.comp.i13.n=4&pt.i0.comp.i26.multi=25&pt.i0.comp.i23.n=5&pt.i0.comp.i14.n=5&pt.i0.comp.i2.multi=1000&pt.i0.comp.i21.type=betline&pt.i0.comp.i27.symbol=SYM12&pt.i0.comp.i1.multi=250&pt.i0.comp.i20.symbol=SYM9&pt.i0.comp.i9.type=betline&pt.i0.comp.i28.multi=10&pt.i0.comp.i26.type=betline&pt.i0.comp.i0.n=3&pt.i0.comp.i30.type=betline&pt.i0.comp.i2.type=betline&pt.i0.comp.i23.symbol=SYM10&pt.i0.comp.i25.type=betline&pt.i0.comp.i16.n=4&pt.i0.comp.i29.multi=25&pt.i0.comp.i27.multi=3&pt.i0.comp.i29.freespins=0&pt.i0.comp.i11.symbol=SYM6&pt.i0.comp.i5.freespins=0&credit=500000&pt.i0.comp.i7.multi=100&pt.i0.comp.i15.n=3&pt.i0.comp.i20.multi=40&pt.i0.comp.i19.symbol=SYM9&pt.i0.comp.i10.symbol=SYM6&pt.i0.comp.i28.symbol=SYM12&pt.i0.comp.i26.symbol=SYM11&pt.i0.comp.i27.type=betline&pt.i0.comp.i3.freespins=0&pt.i0.comp.i24.freespins=0&pt.i0.comp.i1.type=betline&bl.i0.coins=25&bl.i0.id=0&pt.i0.comp.i31.freespins=0&pt.i0.comp.i6.multi=15&pt.i0.comp.i31.n=4&pt.i0.comp.i21.symbol=SYM10&pt.i0.comp.i18.symbol=SYM9&pt.i0.comp.i30.multi=3&pt.i0.comp.i24.symbol=SYM11&pt.i0.comp.i23.freespins=0&pt.i0.comp.i21.n=3&pt.i0.comp.i31.symbol=SYM13&pt.i0.comp.i14.symbol=SYM7&pt.i0.comp.i15.freespins=0&isJackpotWin=false&pt.i0.comp.i4.multi=150&jackpotcurrencyiso=EUR&pt.i0.comp.i18.type=betline&pt.i0.comp.i28.freespins=0&jackpotcurrency=%26%23x20AC%3B&pt.i0.comp.i16.type=betline&playercurrency=%26%23x20AC%3B&g4mode=false&pt.i0.comp.i11.multi=250&pt.i0.comp.i18.n=3&pt.i0.comp.i4.freespins=0&pt.i0.comp.i8.multi=400&pt.i0.comp.i24.multi=3&pt.i0.comp.i1.freespins=0&pt.i0.comp.i24.type=betline&pt.i0.comp.i18.multi=4&pt.i0.comp.i22.multi=15&bl.i0.reelset=ALL&pt.i0.comp.i31.type=betline&pt.i0.comp.i13.freespins=0&pt.i0.comp.i28.n=4&pt.i0.comp.i12.freespins=0&pt.i0.comp.i23.type=betline&playforfun=true&pt.i0.comp.i11.freespins=0&pt.i0.comp.i6.type=betline&pt.i0.comp.i16.symbol=SYM8&pt.i0.comp.i19.multi=15&pt.i0.comp.i13.multi=75&pt.i0.comp.i10.type=betline&pt.i0.comp.i12.type=betline&pt.i0.id=basic&pt.i0.comp.i18.freespins=0&pt.i0.comp.i2.freespins=0&bl.i0.line=0%2F1%2F2%2C0%2F1%2F2%2C0%2F1%2F2%2C0%2F1%2F2%2C0%2F1%2F2&pt.i0.comp.i17.type=betline&pt.i0.comp.i22.type=betline&pt.i0.comp.i25.symbol=SYM11&pt.i0.comp.i26.n=5&pt.i0.comp.i16.multi=15&pt.i0.comp.i14.freespins=0&pt.i0.comp.i26.freespins=0&pt.i0.comp.i17.multi=40&pt.i0.comp.i12.symbol=SYM7&pt.i0.comp.i7.symbol=SYM5');
        return print(urldecode(http_build_query($payTableResponse)));
    }
}
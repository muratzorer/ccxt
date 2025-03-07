<?php

namespace ccxt\async;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import

class binancecoinm extends binance {

    public function describe() {
        return $this->deep_extend(parent::describe (), array(
            'id' => 'binancecoinm',
            'name' => 'Binance COIN-M',
            'urls' => array(
                'logo' => 'https://user-images.githubusercontent.com/1294454/117738721-668c8d80-b205-11eb-8c49-3fad84c4a07f.jpg',
            ),
            'options' => array(
                'defaultType' => 'delivery',
            ),
            // https://www.binance.com/en/fee/deliveryFee
            'fees' => array(
                'trading' => array(
                    'tierBased' => true,
                    'percentage' => true,
                    'taker' => $this->parse_number('0.000500'),
                    'maker' => $this->parse_number('0.000100'),
                    'tiers' => array(
                        'taker' => array(
                            array( $this->parse_number('0'), $this->parse_number('0.000500') ),
                            array( $this->parse_number('250'), $this->parse_number('0.000450') ),
                            array( $this->parse_number('2500'), $this->parse_number('0.000400') ),
                            array( $this->parse_number('7500'), $this->parse_number('0.000300') ),
                            array( $this->parse_number('22500'), $this->parse_number('0.000250') ),
                            array( $this->parse_number('50000'), $this->parse_number('0.000240') ),
                            array( $this->parse_number('100000'), $this->parse_number('0.000240') ),
                            array( $this->parse_number('200000'), $this->parse_number('0.000240') ),
                            array( $this->parse_number('400000'), $this->parse_number('0.000240') ),
                            array( $this->parse_number('750000'), $this->parse_number('0.000240') ),
                        ),
                        'maker' => array(
                            array( $this->parse_number('0'), $this->parse_number('0.000100') ),
                            array( $this->parse_number('250'), $this->parse_number('0.000080') ),
                            array( $this->parse_number('2500'), $this->parse_number('0.000050') ),
                            array( $this->parse_number('7500'), $this->parse_number('0.0000030') ),
                            array( $this->parse_number('22500'), $this->parse_number('0') ),
                            array( $this->parse_number('50000'), $this->parse_number('-0.000050') ),
                            array( $this->parse_number('100000'), $this->parse_number('-0.000060') ),
                            array( $this->parse_number('200000'), $this->parse_number('-0.000070') ),
                            array( $this->parse_number('400000'), $this->parse_number('-0.000080') ),
                            array( $this->parse_number('750000'), $this->parse_number('-0.000090') ),
                        ),
                    ),
                ),
            ),
        ));
    }

    public function fetch_trading_fees($params = array ()) {
        yield $this->load_markets();
        $marketSymbols = is_array($this->markets) ? array_keys($this->markets) : array();
        $fees = array();
        $accountInfo = yield $this->dapiPrivateGetAccount ($params);
        //
        // {
        //      "canDeposit" => true,
        //      "canTrade" => true,
        //      "canWithdraw" => true,
        //      "$feeTier" => 2,
        //      "updateTime" => 0
        //      ...
        //  }
        //
        $feeTier = $this->safe_integer($accountInfo, 'feeTier');
        $feeTiers = $this->fees['trading']['tiers'];
        $maker = $feeTiers['maker'][$feeTier][1];
        $taker = $feeTiers['taker'][$feeTier][1];
        for ($i = 0; $i < count($marketSymbols); $i++) {
            $symbol = $marketSymbols[$i];
            $fees[$symbol] = array(
                'info' => array(
                    'feeTier' => $feeTier,
                ),
                'symbol' => $symbol,
                'maker' => $maker,
                'taker' => $taker,
            );
        }
        return $fees;
    }
}

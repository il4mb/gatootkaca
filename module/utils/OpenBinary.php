<?php
namespace openbinary;

class Binary {

    public $value;

    function __construct($binary)
    {

        $this->value = $binary;
        
    }

    function sumWith($bin) {

        $mathAmount = $this->__match_the_amount__($this->value, $bin);
        $a = str_split($mathAmount[1]);
        $b = str_split($mathAmount[2]);

        //print_r($mathAmount);

        $addition = "";
        $carry = 0;

        for ($x = (count($a) - 1); $x >= 0; $x--) {

            $i = (int)($a[$x]);
            $ii = (int)($b[$x]);

            $isum = $carry + $i + $ii;

            if ($carry > 0) $carry -= 1;

            if ($isum == 3) {

                $addition .= "1";
                $carry = 1;

            } else if ($isum == 2) {

                $addition .= "0";
                $carry += 1;

            } else if ($isum == 1) {

                $addition .= "1";

            } else if ($isum == 0) {

                $addition .= "0";

            }

            if ($x == 0 && $carry > 0) $addition .= $carry;
        }

        $this->value = $this->__flip__($addition);
    }

    function subWith($bin) {

        $mathAmount = $this->__match_the_amount__($this->value, $bin);
        $a = str_split($mathAmount[1]);
        $b = str_split($mathAmount[2]);

        $deducted = "";
        $borrow = 0;

        for ($x = (count($a) - 1); $x >= 0; $x--) {

            $i = (int)($a[$x]);
            $ii = (int)($b[$x]);

            // if it can't be reduce
            if ($i < $ii) {

                // collect unborrowable index elements
                $notEnaught = [];
                $keyColect = [];

                for ( $_x = $x; $_x >= 0; $_x-- ) {

                    if ((int)($a[$_x]) > 0) {
                        $a[$_x] = '0'; // replace borrowed value to zero
                        $borrow += 1;
                        $notEnaught = $keyColect;
                        break; // exit loop
                    }

                    // add index unborrowable
                    array_push($keyColect, $_x);
                }

                // replace unborrowable element to 1
                foreach($notEnaught AS $_x) {
                    $a[$_x] = '1';
                }

                if (count($notEnaught) <= 0) {
                    $borrow -= 1;
                }

            }

            if ($i == 1 && $ii == 1) {

                $deducted .= "0";

            } else if ($i == 1 && $ii == 0) {

                $deducted .= "1";

            } else if ($i == 0 && $ii == 1) {

                // this value can't subtracted
                // so we need to check was borrowed

                if ($borrow > 0) { //if borrow is not zero add 1 to value

                    $deducted .= "1";
                    $borrow -= 1;

                } // else $deducted .= "-0";

            } else if ($i == 0 && $ii == 0) {

                $deducted .= "0";

            }
        }

        if ((int)($deducted) > 0)
            $this->value = $this->__flip__($deducted);
       
    }

    function __match_the_amount__($bin1, $bin2) {

        if(strlen($bin1) > strlen($bin2)) {

            $newBin2 = "";
            $length = (strlen($bin1) - strlen($bin2));

            while($length > 0) {

                $newBin2 .= "0";

                $length -= 1;
            }

            $bin2 = $newBin2 . $bin2;

        } else {

            $newBin1 = "";
            $length = (strlen($bin2) - strlen($bin1));

            while($length > 0) {

                $newBin1 .= "0";

                $length -= 1;
            }

            $bin1 = $newBin1 . $bin1;

        }
        return [
            "1" => $bin1,
            "2" => $bin2
        ];
    }

    function __flip__($binary) {

        $array = str_split($binary);
        $newArr = [];

        for($x = (count($array) -1); $x >= 0; $x--) {

            array_push($newArr, $array[$x]);
        }

        return implode("", $newArr);
    }

    public function __toString() {
        return $this->value;
      }
}
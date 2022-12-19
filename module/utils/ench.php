<?php

namespace Module;

use openbinary\Binary;

#  ENCH - encrypt and decrypt string
#       -- create by ilham b --
#        don`t edit this file

class ENCH
{

    public static function decrypt($string)
    {
        $keysManager = new keysManager();

        $keypos = unpack("C*", substr($string, 0, 1));
        $key = $keysManager->getByKey($keypos[1]-15);
        $charkey = str_split($key);
        $string = substr($string, 1, strlen($string));

        if (strlen($string) > strlen($key)) {

            $key = "";

            $len = strlen($string);

            $i = 0;
            while ($len > 0) {

                $key .= $charkey[$i];

                $i++;
                if ($i >= count($charkey)) $i = 0;


                $len--;
            }
        }
        
        ///////////////////////////////////////////////////////////

        $charkey = unpack('C*', $key);
        $strChar = unpack('C*', $string);

        $output = [];
        foreach($strChar AS $key => $char) {

            $sum = ($char - $charkey[$key]) + 75;

            $output[$key] = $sum;
        }

        return pack("C*", ...$output);

    }


    public static function encrypt($string)
    {

        $keysManager = new keysManager();

        $keypos = $keysManager->getRandomKey();
        $key = $keysManager->getByKey($keypos);
        $charkey = str_split($key);

        if (strlen($string) > strlen($key)) {

            $key = "";

            $len = strlen($string);

            $i = 0;
            while ($len > 0) {

                $key .= $charkey[$i];

                $i++;
                if ($i >= count($charkey)) $i = 0;


                $len--;
            }
        }

        ///////////////////////////////////////////////////////////

        $charkey = unpack('C*', $key);
        $strChar = unpack('C*', $string);

        $output = [];
        foreach($strChar AS $key => $char) {

            $sum = ($char + $charkey[$key]) - 75;

            $output[$key] = $sum;
        }

        return pack("C*", $keypos+15).pack("C*", ...$output);
    }
}

class keysManager
{
    private $list = ["Alligator", "Albatros", "Alpaka", "Ular anaconda", "Ikan teri", "Anoa", "Semut", "Trenggiling", "Antelop", "Kera", "Babon", "Luwak", "Kelelawar", "Biwara", "Lebah", "Kumbang", "Burung", "Bison", "Ular boa", "Babi hutan", "Kucing hutan", "Banteng", "Anjing Bulldog", "Kupu-kupu", "Rajawali", "Anak sapi", "Unta", "Kenari", "Kasuari", "Kucing", "Ulat bulu", "Lele", "Kelabang", "Bunglon", "Ayam", "Simpanse", "Tupai tanah", "Kerang", "Tongkol", "Ular kobra", "Kakatua", "Kecoa", "Sapi", "Anjing hutan", "Kepiting", "Jangkrik", "Buaya", "Gagak", "Kuskus", "Rusa", "Anjing", "Lumba-Lumba", "Keledai", "Burung Dara", "Capung", "Bebek", "Elang", "Belut", "Gajah", "Rusa besar", "Falkon", "Kunang-Kunang", "Ikan", "Flamingo", "Kutu", "Lalat", "Laron", "Rubah", "Katak", "Cicak", "Siamang", "Jerapah", "Kambing", "Soang", "Belalang", "Belibis", "Marmut", "Hamster", "Elang",  "Ayam betina", "Bangau", "Kuda nil", "Rangkong", "Tabuhan", "Kuda", "Kolibri", "Iguana", "Jaguar", "Ubur-Ubur", "Kanguru",  "Anak kucing", "Burung kiwi", "Koala", "Komodo", "Kepik", "Anak domba", "Lutung", "Lintah", "Macan tutul", "Kutu rambut", "Singa",  "Kadal", "Llama", "Lobster", "Belatung", "Murai", "Maleo", "Mamut", "Belalang Sembah", "Bandeng", "Monyet", "Nyamuk", "Ngengat",  "Tikus", "Kancil", "Remis", "Kadal air", "Bulbul", "Gurita", "Burung unta", "Berang-berang", "Burung hantu", "Kerbau", "Tiram",  "Panda", "Harimau Kumbang", "Beo", "Ayam Hutan", "Merak", "Pelikan", "Babi", "Merpati", "Platipus", "Beruang kutub", "Landak",  "Anak anjing", "Ular piton", "Puyuh", "Kelinci", "Tikus besar", "Ular derik", "Gagak besar", "Badak", "Ayam jantan", "Salamander",  "Sarden", "Kalajengking", "Singa laut", "Burung camar", "Kuda laut", "Anjing laut", "Hiu", "Domba", "Udang", "Kukang", "Siput",  "Ular", "Kakap", "Burung gereja", "Laba-laba", "Cumi-cumi", "Tupai", "Bintang laut", "Ikan pari", "Cerpelai", "Burung layang",  "Angsa putih", "Ikan cucut", "Kecebong", "Tarantula", "Mujair", "Rayap", "Harimau", "Kodok", "Kura-kura", "Ikan tuna", "Kalkun", "Penyu", "Perkutut", "Ular berbisa", "Burung bangkai", "Singa Laut", "Tawon", "Musang", "Paus", "Serigala", "Burung pelatuk", "Cacing", "Zebra"];
    public function __construct()
    {
    }
    public function getByKey(int $key)
    {
        return $this->list[$key];
    }
    public function getRandomKey()
    {
        return array_rand($this->list);
    }
}

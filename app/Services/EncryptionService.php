<?php

namespace App\Services; // Pastikan namespace benar

class EncryptionService
{
    // Karakter set mod 62 (a-z, A-Z, 0-9)
    private $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private $mod;

    public function __construct()
    {
        $this->mod = strlen($this->charset);
    }

    /**
     * Enkripsi 3 Lapis: Playfair -> Caesar -> Vigenere
     */
    public function encrypt(string $plainText, string $key): string
    {
        $cleanedText = preg_replace('/[^a-zA-Z0-9]/', '', $plainText); // Bersihkan teks
        $playfairEncrypted = $this->playfairProcess($cleanedText, $key, 'encrypt');
        $caesarEncrypted = $this->caesarProcess($playfairEncrypted, $key, 'encrypt');
        return $this->vigenereProcess($caesarEncrypted, $key, 'encrypt');
    }

    /**
     * Dekripsi 3 Lapis: Vigenere -> Caesar -> Playfair
     */
    public function decrypt(string $cipherText, string $key): string
    {
        $vigenereDecrypted = $this->vigenereProcess($cipherText, $key, 'decrypt');
        $caesarDecrypted = $this->caesarProcess($vigenereDecrypted, $key, 'decrypt');
        return $this->playfairProcess($caesarDecrypted, $key, 'decrypt');
    }

    /**
     * Fungsi untuk mendapatkan hasil tiap lapisan enkripsi.
     */
    public function getAllEncryptionSteps(string $plainText, string $key): array
    {
        $cleanedText = preg_replace('/[^a-zA-Z0-9]/', '', $plainText);
        $playfairResult = $this->playfairProcess($cleanedText, $key, 'encrypt');
        $caesarResult = $this->caesarProcess($playfairResult, $key, 'encrypt');
        $vigenereResult = $this->vigenereProcess($caesarResult, $key, 'encrypt');

        return [
            'playfair' => $playfairResult,
            'caesar'   => $caesarResult,
            'vigenere' => $vigenereResult,
        ];
    }

    private function caesarProcess(string $text, string $pin, string $mode): string
    {
        $shift = $this->pinToShift($pin);
        if ($mode === 'decrypt') {
            $shift = -$shift; // Shift negatif untuk dekripsi
        }
        return $this->shiftText($text, $shift);
    }

    /**
     * Konversi PIN jadi angka shift untuk Caesar (dari contoh CryptoHelper Anda)
     */
    private function pinToShift(string $pin): int
    {
        $sum = 0;
        for ($i = 0; $i < strlen($pin); $i++) {
            // ord() mendapatkan nilai ASCII dari karakter
            $sum += ord($pin[$i]); 
        }
        // Hasilnya adalah sisa bagi dari total nilai ASCII dengan jumlah karakter (62)
        return $sum % $this->mod; // shift antara 0 - 61
    }

    /**
     * Fungsi geser teks (digunakan oleh Caesar - dari contoh CryptoHelper Anda)
     */
    private function shiftText(string $text, int $shift): string
    {
        $result = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $pos = strpos($this->charset, $char);

            if ($pos !== false) {
                // Tambah $this->mod untuk handle shift negatif saat dekripsi
                $newPos = ($pos + $shift + $this->mod) % $this->mod; 
                $result .= $this->charset[$newPos];
            } else {
                $result .= $char; // Biarkan karakter non-charset (jika ada)
            }
        }
        return $result;
    }


    private function vigenereProcess(string $text, string $pin, string $mode): string
    {
        $result = '';
        $pinLength = strlen($pin);
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            $pos = strpos($this->charset, $char);

            if ($pos !== false) {
                $keyChar = $pin[$i % $pinLength]; // Kunci berulang
                $keyShift = strpos($this->charset, $keyChar);

                if ($keyShift !== false) { // Pastikan karakter kunci ada di charset
                   if ($mode === 'encrypt') {
                       $newPos = ($pos + $keyShift) % $this->mod;
                   } else { // decrypt
                       $newPos = ($pos - $keyShift + $this->mod) % $this->mod; // Tambah mod
                   }
                   $result .= $this->charset[$newPos];
                } else {
                    $result .= $char; // Abaikan jika karakter kunci aneh
                }
            } else {
                $result .= $char; // Biarkan karakter non-charset
            }
        }
        return $result;
    }

     private function playfairProcess(string $text, string $key, string $mode): string
    {
        // Gunakan '9' sebagai filler matrix (karakter terakhir charset)
        $matrix = $this->generatePlayfairMatrix($key, '9'); 
        $text = $this->preparePlayfairText($text, $mode);
        $result = '';
        
        for ($i = 0; $i < strlen($text); $i += 2) {
             if (!isset($text[$i+1])) continue; // Pengaman

            $pos1 = $this->findPositionInMatrix($matrix, $text[$i]);
            $pos2 = $this->findPositionInMatrix($matrix, $text[$i + 1]);
            
            // Pengaman jika karakter tidak ditemukan di matriks (seharusnya tidak terjadi)
            if($pos1['row'] == -1 || $pos2['row'] == -1) {
                $result .= $text[$i] . $text[$i+1]; // Kembalikan pasangan karakter asli jika error
                continue;
            }

            $shift = ($mode === 'encrypt') ? 1 : -1;

            if ($pos1['row'] === $pos2['row']) { // Baris sama
                $result .= $matrix[$pos1['row']][($pos1['col'] + $shift + 8) % 8];
                $result .= $matrix[$pos2['row']][($pos2['col'] + $shift + 8) % 8];
            } elseif ($pos1['col'] === $pos2['col']) { // Kolom sama
                $result .= $matrix[($pos1['row'] + $shift + 8) % 8][$pos1['col']];
                $result .= $matrix[($pos2['row'] + $shift + 8) % 8][$pos2['col']];
            } else { // Persegi
                $result .= $matrix[$pos1['row']][$pos2['col']];
                $result .= $matrix[$pos2['row']][$pos1['col']];
            }
        }
        // Hapus 'X' padding saat dekripsi
        return ($mode === 'decrypt') ? rtrim($result, 'X') : $result;
    }

    /**
     * Generate matriks Playfair 8x8, gunakan karakter filler yang ditentukan.
     */
    private function generatePlayfairMatrix(string $key, string $filler = '9'): array
    {
        // Buat string unik dari kunci + charset
        $matrixChars = implode('', array_unique(str_split($key . $this->charset)));
        // Hapus filler dari string jika kebetulan ada di key/charset awal
        $matrixChars = str_replace($filler, '', $matrixChars); 
        // Tambahkan filler di akhir agar unik dan pasti ada
        $matrixChars .= $filler; 
        
        // Isi matriks 8x8 (64 sel), sisa karakter diisi dengan $filler
        return array_chunk(str_split(str_pad($matrixChars, 64, $filler)), 8);
    }

    /**
     * Persiapkan teks untuk Playfair (handle huruf ganda dan panjang ganjil)
     */
    private function preparePlayfairText(string $text, string $mode): string
    {
        if ($mode !== 'encrypt') return $text;

        $prepared = '';
        $charX = 'X'; // Karakter sisipan, pastikan ada di $charset

        for ($i = 0; $i < strlen($text); $i++) {
            $prepared .= $text[$i];
            // Sisipkan $charX jika karakter berikutnya sama
            if (isset($text[$i + 1]) && $text[$i] === $text[$i + 1]) {
                $prepared .= $charX;
            }
        }
        // Tambahkan $charX jika panjang ganjil
        if (strlen($prepared) % 2 !== 0) {
            $prepared .= $charX;
        }
        return $prepared;
    }

    /**
     * Cari posisi karakter dalam matriks Playfair
     */
    private function findPositionInMatrix(array $matrix, string $char): array
    {
        for ($row = 0; $row < 8; $row++) {
            for ($col = 0; $col < 8; $col++) {
                if ($matrix[$row][$col] === $char) return ['row' => $row, 'col' => $col];
            }
        }
        // Kembalikan -1 jika tidak ditemukan
        return ['row' => -1, 'col' => -1]; 
    }
}
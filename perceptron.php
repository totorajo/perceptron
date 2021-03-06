<?php
class Perceptron{
	
	private $learning_rate;
	private $weight;
	private $bias;
	
	function set_learning_rate($learning_rate){		// function untuk mengeset learning rate
		$this->learning_rate = $learning_rate;
	}
	
	function set_weight($weight){					// function untuk mengeset bobot awal
		$this->weight = $weight;
	}
	
	function set_bias($bias){ 						// function untuk mengeset bias awal
		$this->bias = $bias;
	}
	
	function training($data,$label,$max_epoh){      // function pelatihan
		for($a = 0;$a < $max_epoh;$a++){			// perulangan sebanyak epoh
			$cek ="";
			echo "Epoh Ke : ".($a+1)."</br>";
			for($x = 0; $x < count($data); $x++) {	// perulangan untuk mengolah data per baris
				$weight = $this->weight;
				$bias = $this->bias;
				$y = $this->dot_product($data[$x],$weight,$bias);	// melakukan perhitungan nilai y
				if($y == $label[$x]){			// jika output sama dengan label, bobot dan bias tidak berubah
					$cek .="1";
					$this->weight = $weight;
					$this->bias = $bias;
				}else{							// jika output berbeda, hitung error dan update bobot beserta bias
					$cek .="0";
					$error = $label[$x] - $y;
					$this->weight = $this->update_weight($weight,$this->learning_rate,$error,$data[$x]);
					$this->bias = $this->update_bias($bias,$this->learning_rate,$error);
				}
				echo "Bobot : ";
				for($b=0;$b<count($this->weight);$b++){
					echo $this->weight[$b]."   ";
				}
				echo "Bias : ".$this->bias."</br>";
			}
			$ck = strpos($cek,"0");					// cek apakah dalam satu iterasi ada error
			if($ck===FALSE){						// jika tidak ada error, iterasi dihentikan
				$a = $max_epoh;
			}
		}
		$out = array($this->weight,$this->bias);
		return $out;
	}
	
	function update_weight($weight,$learning_rate,$error,$data){	// function update bobot 
		$weight_new = array();
		for($x = 0;$x < count($weight);$x++){
			$weight_new[$x] = $weight[$x] +($learning_rate*$error*$data[$x]);
		}
		return $weight_new;
	}
	
	function update_bias($bias,$learning_rate,$error){				// function update bobot bias
		$bias_new = $bias +($learning_rate*$error);
		return $bias_new;
	}
	
	function dot_product($data,$weight,$bias){	// menghitung nilai y_in,
		$y_in = 0;
		for($x = 0;$x < count($data);$x++){
			$y_in = $y_in + ($data[$x]*$weight[$x]);
		}
		$y = $y_in + ($bias);
		return $this->sign($y);					// mengaktivasi y_in
	}	
	
	function sign($y_in){						// fungsi aktivasi undak biner
		if($y_in>=0){
			$y = 1;
		}else{
			$y = 0;
		}
		return $y;
	}
	
	function classification($data,$weight,$bias){
		return $this->dot_product($data,$weight,$bias);
	}
}

/////data latih
$data = array(
	array(3,2,1),
	array(1,1,3),
	array(1,0,0),
	array(1,1,1)
	);
$label = array(1,1,0,1);	// label atau target
$learning_rate = 1;			// learning rate
$bias = 0;					// bobot bias awal
$max_epoh = 10;				// maksimal iterasi
$weight = array(0,0,0);		// bobot awal

$nn = new Perceptron();	
$nn->set_learning_rate($learning_rate);
$nn->set_weight($weight);
$nn->set_bias($bias);
echo "proses pelatihan :</br>";
$out = $nn->training($data,$label,$max_epoh);	//melakukan training untuk mendapatkan bobot dan bias 

$bobot = $out[0];
$bias = $out[1];
$data_uji = array(1,1);		// data uji (silahkan ubah data ini untuk melakukan pengujian bobot akhir)
$hasil = $nn->classification($data_uji,$bobot,$bias); // melakukan proses klasifikasi dengan data uji
echo $hasil;				// hasil klasifikasi
?>

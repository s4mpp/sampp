<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	require('application/third_party/fPDF/fpdf.php');
	class PDF extends FPDF {

		// Extend FPDF using this class
		// More at fpdf.org -> Tutorials
		
		function __construct($config) {
			// Call parent constructor

			$orientation = (isset($config['orientation'])) ? $config['orientation'] : 'P';
			$unit = (isset($config['unit'])) ? $config['unit'] : 'mm';
			$size = (isset($config['size'])) ? $config['size'] : 'A4';
			$this->title = (isset($config['title'])) ? $config['title'] : 'DOCUMENTO PDF';
			$this->size = $size;

			$this->tamMM = 0;

			parent::__construct($orientation, $unit, $size);
			$this->setTitle($this->title);
			
			if($orientation == 'P') {
				switch($size) {
					case 'A4' : $this->setTamMM('190'); break;
					case 'A5' : $this->setTamMM('128'); break;
				}
				
			} else {
				switch($size) {
					case 'A4' : $this->setTamMM('279'); break;
					case 'A5' : $this->setTamMM('190'); break;
				}
			}

			$CI =& get_instance();
			$CI->db->select('instalacao.nome, instalacao.endereco, instalacao.bairro, instalacao.num_endereco, instalacao.cep, municipios.nome as nomeCidade');
			$CI->db->from('instalacao');
			$CI->db->join('municipios', 'municipios.id = instalacao.cidade', 'left');
			$this->cabecalho = $CI->db->get()->result()[0];

			$this->cabecalho->num_endereco = ($this->cabecalho->num_endereco) ? $this->cabecalho->num_endereco : 's/n';


		}


		function setTamMM($mm) {
			$this->tamMM = $mm;
		}



		function getSizeCell($percent) {
			$sizePercent = $this->tamMM / (100 / $percent);
			return floor($sizePercent);
		}


		public function Header() {

			$this->SetFont('Arial', null, 8);
			$this->AliasNbPages();
			
			$this->SetXY($this->tamMM/1.9, 10);

			$img = 'assets/img/logo.png';

			if($this->size == 'A4') {

				$this->Image($img,10,10, null, 13);

				$this->setFontSize(9);
				$this->SetFont(null, 'B');
				$this->Cell($this->tamMM/1.9, 4, $this->cabecalho->nome, 0, 2,'C');
				$this->SetFont(null, null);
				$this->Cell($this->tamMM/1.9, 4, $this->cabecalho->endereco.', '.$this->cabecalho->num_endereco.', '.$this->cabecalho->bairro, 0, 2,'C');
				$this->Cell($this->tamMM/1.9, 4, $this->cabecalho->nomeCidade, 0, 2,'C');

			} else {
				$this->Image($img,33,10, null, 15);	
			}

			$this->setFontSize(12);
			$this->SetXY(10,30);
			$this->SetFont(null);
			$this->Cell(0, 0, '', 'T', 1,'C');
			$this->SetXY(10,31);
			$this->Cell(0, 8, $this->title, 'B', 1,'C');
			$this->SetXY(10,40);
			$this->SetFont(null, null);
			$this->setFontSize(10);
		
		}
			
		public function Footer() {
			$this->SetY(-10);
			$this->setFontSize(9);
			$this->Cell(0,0,'Emitido em '.date('d/m/Y H:i', strtotime(date('Y-m-d H:i'))), 0, 0,'L');
			$this->Cell(5,0,'Página '.$this->PageNo().' de {nb}',0,0,'R');
		}


		function param_relatorios($fields, $registros) {
			$this->SetFont('Courier');
			$this->setY($this->getY() + 4);

			foreach($registros as $key => $value) {

				if($value[0] == 'TOTAL') {
					$this->setY($this->getY() + 3);
					$this->setFont(null, 'B');
				}

				$this->Cell(0, 5, str_pad($value[0], 20, '.').': '.$value[1],  0, 2, 'L');
			}

			$this->SetFont('Arial');
			$this->setY($this->getY() + 5);

		}




		function gerar_header_tb_relatorio() {

			$CI =& get_instance();

			//Pega os dados recebidos
			$dados = $CI->input->post();


			//Cria os arrays q serao usados
			$campos = array();
			$fields = array();

			//tira valores vazios do array;
			$dados = array_filter($dados);
			//Percorre o array
			foreach($dados as $dado) {
				//Verifica se é um array e se o campo de porcentagem nao esta vazio
				if(is_array($dado) && !empty($dado[2])) {

					//Coloca o campo com os labels na variavel $campos
					array_push($campos, $dado[0]);
					//Coloca os campos value, conteudo e tamanho na variavel fiedls
					array_push($fields, array($dado[0], $dado[1], $dado[2]));
				}
			}

			$rel['fields'] = $fields;
			$rel['campos'] = $campos;

			return $rel;
			

		}

		function gerar_conteudo_relatorio($rel, $registros) {

			//Faz o loop no cabeçalho da tabela
			$this->SetFillColor(220,220,220);
			$this->SetFontSize(9);
			for($i=0; $i<count($rel['fields']); $i++) {
				//a variavel ln indica se é a ultima celular, para descer pra linha de baixo
				$ln = ($i+1 == count($rel['fields'])) ? 1 : 0;
				//Gera as celula
				$this->Cell($this->getSizeCell($rel['fields'][$i][2]), 5, $rel['fields'][$i][1], 'B', $ln, 'L', 1);
			}

			$this->SetFontSize(6);
			$this->SetFillColor(245,245,245);

			//Faz o loop entre os registros gerando a linha
			for($i=0; $i<count($registros); $i++) {
				//Faz o loop entre os campos da linha
				for($j=0; $j<count($rel['fields']); $j++) {
					//a variavel ln indica se é a ultima celula, para descer pra linha de baixo
					$ln = ($j+1 == count($rel['fields'])) ? 1 : 0;
					//Verifica se a coluna é impar para fazer o preenchimento de cor de fundo
					$fill = ($i % 2 == 0) ? 0 : 1;
					//Gera a celula
					$this->Cell($this->getSizeCell($rel['fields'][$j][2]), 4, $registros[$i][$rel['fields'][$j][0]], null, $ln, 'L', $fill);
				}
			}

		}



	}
?>
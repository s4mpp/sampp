<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Form_validation Class
 *
 * Extends Form_Validation library
 *
 */
class MY_Form_validation extends CI_Form_validation {
	function __construct()
	{
	    parent::__construct();
	}
	// --------------------------------------------------------------------
	/**
	 * Unique
	 *
	 * Verifica se o valor já está cadastrado no banco
	 * unique[users.login] retorna FALSE se o valor postado já estiver no campo login da tabela users
	 * unique[users.login.10] retorna FALSE se o valor postado já estiver no campo login da tabela users, desde que o id seja diferente de 10.
	 * 						isso é útil quando for atualizar os dados
	 * unique[users.city.10:id_cidade] retorna FALSE se o valor postado já estiver no campo city da tabela users, desde que o id_cidade seja diferente de 10.
	 						se não for passado o valor após o : será usado o id.
	 * @access	public
	 * @param	string - dados que será buscado
	 * @param	string - campo, tabela e id
	 *
	 * @return	bool
	 */
	function unique($str = '', $field = '')
	{
		$CI =& get_instance();
		
		$res = explode('.', $field, 3);
		
		$table	= $res[0];
		$column	= $res[1];
		$CI->form_validation->set_message('unique', 'O %s informado já está cadastrado.');
		
		
		$CI->db->select('COUNT(*) as total');
		$CI->db->where($column, $str);
		
		if( isset($res[2]) )
		{
			$res2 = explode(':', $res[2], 2);
			$ignore_value = $res2[0];
			
			if( isset($res2[1]) )
				$ignore_field = $res2[1];
			else
				$ignore_field = 'id';
			
			$CI->db->where($ignore_field . ' !=', $ignore_value);
		}
		$total = $CI->db->get($table)->row()->total;
		return ($total > 0) ? FALSE : TRUE;
	}
    /**
     *
     * decimar_br
	 *
	 * Verifica se é decimal, mas com virgula no lugar de .
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function decimal_br($str)
	{
		$CI =& get_instance();
        $CI->form_validation->set_message('decimal_br', 'O campo %s não contem um valor decimal válido.');
        return (bool) preg_match('/^[\-+]?[0-9]+\,[0-9]+$/', $str);
	}
    /**
     *
     * valid_cpf
     *
     * Verifica CPF é válido
     * @access	public
     * @param	string
     * @return	bool
     */
    function valid_cpf($cpf)
    {
        $CI =& get_instance();
        
        $CI->form_validation->set_message('valid_cpf', 'O %s informado não é válido.');
        $cpf = preg_replace('/[^0-9]/','',$cpf);
        if(strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf))
        {
            return false;
        }
        // 9 primeiros digitos do cpf
        $digit = substr($cpf, 0, 9);
        // calculo dos 2 digitos verificadores
        for($j=10; $j <= 11; $j++)
        {
            $sum = 0;
            for($i=0; $i< $j-1; $i++)
            {
                $sum += ($j-$i) * ((int) $digit[$i]);
            }
            $summod11 = $sum % 11;
            $digit[$j-1] = $summod11 < 2 ? 0 : 11 - $summod11;
        }
        
        return $digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10]);
    }
    /**
     * valid_date
     *
     * valida data no pradrao brasileiro
     * 
     * @access	public
     * @param	string
     * @return	bool
     */
    function valid_date($data)
    {
        $CI =& get_instance();
        $CI->form_validation->set_message('valid_date', 'O campo %s não contém uma data válida.');
        $padrao = explode('/', $data);
        return checkdate($padrao[1], $padrao[0], $padrao[2]);
    }
    /**
     * valid_cep
     *
     * Verifica se CEP é válido
     * 
     * @access	public
     * @param	string
     * @return	bool
     */
    function valid_cep($cep)
    {
        $CI =& get_instance();
        $CI->form_validation->set_message('valid_cep', 'O campo %s não contém um CEP válido.');
        $cep = str_replace('.', '', $cep);
        $cep = str_replace('-', '', $cep);
        $url = 'http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 0);
        $resultado = curl_exec($ch);
        curl_close($ch);
        if( ! $resultado)
            $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
        $resultado = urldecode($resultado);
        $resultado = utf8_encode($resultado);
        parse_str( $resultado, $retorno);
        if($retorno['resultado'] == 1 || $retorno['resultado'] == 2)
            return TRUE;
        else
            return FALSE;
    }
    /**
     * valid_phone
     *
     * validação simples de telefone
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    function valid_phone($fone)
    {
        $CI =& get_instance();
        $CI->form_validation->set_message('valid_fone', 'O campo %s não contém um Telefone válido.');
        $fone = preg_replace('/[^0-9]/','',$fone);
        $fone = (string) $fone;
        if( strlen($fone) >= 10)
            return TRUE;
        else
            return FALSE;
    }

    function alpha_spaces($str) {
        $CI =& get_instance();
        $CI->form_validation->set_message('alpha_spaces', 'O campo %s pode conter apenas letras e espaços, sem acentos.');


        if (!preg_match('/^[a-z .,\-]+$/i', $str))
            return false;
        else
            return true;
    }

    public function error_array() {
        $error = $this->_error_array;

        $erros = array();
        foreach($error as $key => $value) {
           $erros[] = array('error', $value, $key);
        }

        return $erros;
    }
}
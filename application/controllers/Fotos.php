<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fotos extends CI_Controller {

  function __construct()
  {
    parent::__construct();

  $this->load->model('UserModel');
  $this->load->model('PersonModel');
  $this->load->model('AlbumModel');
  $this->load->model('ImagexAlbumModel');
  $this->load->model('ImageModel');

  }

public function guardarAlbum(){
  if($this->session->has_userdata('id')){
    $data['head'] = 'bienvenido';
    $this->load->view('header', $data);
    $this->load->view('IngresarAlbum');
    $this->load->view('footer');
  }else{
    $data['head'] = 'Login';
   $this->load->view('header', $data);
  $this->load->view('login');
   $this->load->view('footer');
  }
}


public function registrarAlbum(){
  if($this->session->has_userdata('id')){
      $dta['head']="Crear album";
    $dta['userid']=$this->session->userdata('id');
    $dta['name']=$this->input->post('name');
    $dta['description']=$this->input->post('description');
    $album = new AlbumModel();
  if($album->validate()){
  $album->setData($dta);
  $album->insertar();
    $this->session->set_flashdata('message', 'Album Creado!');
        redirect('Fotos/guardarAlbum');
    }
    else {

    //    $this->session->set_flashdata('message', '¡Porfavor rellenar todos los campos!');
        //redirect('perfil/registrar');
        $this->load->view('header',$data);
        $this->load->view('guardarAlbum');
        $this->load->view('footer');
    }
  }else{
    $data['head'] = 'Login';
   $this->load->view('header', $data);
  $this->load->view('login');
   $this->load->view('footer');
  }

}



public function guardarImagen(){
  if($this->session->has_userdata('id')){
    $data['head'] = 'Ingresar una foto';
    $this->load->view('header', $data);
    $this->load->view('IngresarFoto');
    $this->load->view('footer');
  }else{
    $data['head'] = 'Login';
   $this->load->view('header', $data);
  $this->load->view('login');
   $this->load->view('footer');
  }
}

public function registrarImagen(){
  if($this->session->has_userdata('id')){

      $data['head'] = 'Ingresar una foto';
    ini_set('post_max_size', '164M');
    ini_set('upload_max_filesize', '64M');
    $config['upload_path']          = './uploads/Albums';
    $config['allowed_types']        = 'gif|jpg|png';
    $config['encrypt_name']         = true;

    $this->load->library('upload', $config);

    if ( ! $this->upload->do_upload('photo'))
    {
            $error = array('error' => $this->upload->display_errors());

            //$this->load->view('upload_form', $error);
    }
    else {

            $data2 = array('upload_data' => $this->upload->data());
            //$this->load->view('upload_success', $data);
    }
    $dataimg['tittle']=$this->input->post('tittle');
    $dataimg['description']=$this->input->post('description');
    $dataimg['coments']=$this->input->post('coments');
    $dataimg['photo']=$this->upload->data('file_name');
    $image = new ImageModel();
    if($image->validate()){
      $image->setData($dataimg);
      $image->insertar();
      $this->session->set_flashdata('message', 'Foto subida!');
        redirect('Fotos/guardarImagen');
    }
    else {

    //    $this->session->set_flashdata('message', '¡Porfavor rellenar todos los campos!');
        //redirect('perfil/registrar');
        $this->load->view('header',$data);
        $this->load->view('IngresarFoto');
        $this->load->view('footer');
    }



  }else{
    $data['head'] = 'Login';
   $this->load->view('header', $data);
  $this->load->view('login');
   $this->load->view('footer');
  }

}

public function guardarImagenxAlbum(){
  if($this->session->has_userdata('id')){
    $data['head'] = 'Agregar imagenes a un album';
    $data['imagenes']= $this->ImageModel->getall();
    $data['albums']= $this->AlbumModel->getUsersAlbum($this->session->userdata('id'));
    //var_dump($data);
  //  exit();
    $this->load->view('header', $data);
    $this->load->view('AgregarImagen',$data);
    $this->load->view('footer');
  }else{
    $data['head'] = 'Login';
   $this->load->view('header', $data);
  $this->load->view('login');
   $this->load->view('footer');
  }
}

  public function index()
	{
    if($this->session->has_userdata('id')){
      $data['head'] = 'bienvenido';
      $this->load->view('header', $data);
      $this->load->view('ListaAlbums');
      $this->load->view('footer');
    }else{
      $data['head'] = 'Login';
     $this->load->view('header', $data);
 		$this->load->view('login');
     $this->load->view('footer');
    }
  }

public function logout(){
  $this->session->sess_destroy();
  redirect("Fotos/index");
}

    public function login()
    {
      if(!$this->session->has_userdata('id')){
        $userdata['nickName']=$this->input->post('nickName');
        $userdata['pass']=$this->input->post('pass');
        $user= new UserModel();
        if($user->loginValidate()){
          $user->setData($userdata);

        if($user->loguear()){
          $data['head'] = 'bienvenido';
          $this->load->view('header', $data);
          $this->load->view('ListaAlbums');
          $this->load->view('footer');
        }else{
          $this->session->set_flashdata('message', 'Usuario y contraseña invalidos');
          redirect('Fotos/index');
        }
      }else{
        $data['head'] = 'Login';
       $this->load->view('header', $data);
   		$this->load->view('login');
       $this->load->view('footer');
      }
      }else{
        $data['head'] = 'bienvenido';
        $this->load->view('header', $data);
        $this->load->view('ListaAlbums');
        $this->load->view('footer');
      }
    }

    public function registrarUsuario(){
        if(!$this->session->has_userdata('id')){
      $data['head'] = 'Registro';
      $this->load->view('header', $data);
      $this->load->view('IngresarUsuario');
      $this->load->view('footer');
    }else{
      $data['head'] = 'bienvenido';
      $this->load->view('header', $data);
      $this->load->view('ListaAlbums');
      $this->load->view('footer');
    }
    }

    public function guardar()
    {

      //Machetazo
      // $usuario_auxiliar = new Usuario_m();
      // $mayorId = $usuario_auxiliar->getMaxId();
      ini_set('post_max_size', '164M');
      ini_set('upload_max_filesize', '64M');
      $config['upload_path']          = './uploads/Avatars';
      $config['allowed_types']        = 'gif|jpg|png';
      $config['encrypt_name']         = true;

      $this->load->library('upload', $config);

      if ( ! $this->upload->do_upload('avatar'))
      {
              $error = array('error' => $this->upload->display_errors());

              //$this->load->view('upload_form', $error);
      }
      else {

              $data2 = array('upload_data' => $this->upload->data());
              //$this->load->view('upload_success', $data);
      }
      $persondata['id']=$this->input->post('id');
      $persondata['name']=$this->input->post('name');

      $userdata['idPerson']=$this->input->post('id');
      $userdata['id']=$this->input->post('id');
      $userdata['nickName']=$this->input->post('nickName');
      $userdata['pass']=$this->input->post('pass');
      $userdata['avatar']=$this->upload->data('file_name');
      $userdata['rol']=$this->input->post('rol');

      $albumdata["name"]="default";
      $albumdata["userid"]=$this->input->post('id');;
      $albumdata["description"]="default";

      $persona = new PersonModel();
      $usuario = new UserModel();
      $album = new AlbumModel();
      if($usuario->validate() and $persona->validate()){
          $persona->setData($persondata);
          $usuario->setData($userdata);
          $album->setData($albumdata);
          $persona->insertar();
          $usuario->insertar();
          $album->insertar();
          $this->session->set_flashdata('message', '¡Usuario Creado!');
          redirect('Fotos/registrarUsuario');
      }
      else {

      //    $this->session->set_flashdata('message', '¡Porfavor rellenar todos los campos!');
          //redirect('perfil/registrar');
          $this->load->view('header',$data);
          $this->load->view('registrarUsuario');
          $this->load->view('footer');
      }


    }
}

?>

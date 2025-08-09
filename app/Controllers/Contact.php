<?php

namespace App\Controllers;

class Contact extends BaseController
{
    public function index()
    {
        return view('contact/index');
    }

    public function send()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name'    => 'required|min_length[3]|max_length[100]',
            'email'   => 'required|valid_email',
            'comment' => 'required|min_length[10]',
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->to('/contacto')->withInput()->with('errors', $validation->getErrors());
        }

        // Recoger todos los datos del formulario
        $name            = $this->request->getPost('name');
        $email           = $this->request->getPost('email');
        $message         = $this->request->getPost('comment');
        $documentId      = $this->request->getPost('document_id');
        $cellphoneNumber = $this->request->getPost('cellphone_number');
        $subject         = $this->request->getPost('subject');

        // Simular envío de correo (puedes agregar los datos extra al mensaje)
        $logMsg = "Mensaje de contacto recibido:\n" .
            "Nombre: $name\n" .
            "Email: $email\n" .
            "Documento: $documentId\n" .
            "Celular: $cellphoneNumber\n" .
            "Tipo de consulta: $subject\n" .
            "Mensaje: $message";
        log_message('info', $logMsg);

        // Aquí iría la lógica real para enviar el correo, incluyendo los datos extra si lo deseas
        // ...existing code...

        return redirect()->to('/contacto')->with('message', 'Tu mensaje ha sido enviado con éxito. Nos pondremos en contacto contigo pronto.');
    }
}

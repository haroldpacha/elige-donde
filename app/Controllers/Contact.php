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
        $rules = [
            'name'    => 'required|min_length[3]|max_length[100]',
            'email'   => 'required|valid_email',
            'message' => 'required|min_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simular envío de correo
        $name    = $this->request->getPost('name');
        $email   = $this->request->getPost('email');
        $message = $this->request->getPost('message');

        // Aquí iría la lógica real para enviar el correo, por ejemplo, usando la clase Email de CodeIgniter
        // $emailService = \Config\Services::email();
        // $emailService->setTo('info@tudominio.com');
        // $emailService->setFrom($email, $name);
        // $emailService->setSubject('Mensaje de Contacto desde el sitio web');
        // $emailService->setMessage($message);
        // if ($emailService->send()) {
        //     return redirect()->to('/contacto')->with('message', 'Tu mensaje ha sido enviado con éxito.');
        // } else {
        //     return redirect()->back()->withInput()->with('error', 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.');
        // }

        // Para propósitos de demostración, solo mostraremos un mensaje de éxito
        log_message('info', 'Mensaje de contacto recibido de ' . $name . ' (' . $email . '): ' . $message);

        return redirect()->to('/contacto')->with('message', 'Tu mensaje ha sido enviado con éxito. Nos pondremos en contacto contigo pronto.');
    }
}

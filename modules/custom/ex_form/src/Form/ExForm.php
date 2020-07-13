<?php

namespace Drupal\ex_form\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\Plugin\Mail\PhpMail;


/**
 * @see \Drupal\Core\Form\FormBase
 */
class ExForm extends FormBase {

    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Ваше имя'),
            '#required' => TRUE,
        ];

        $form['surname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Вашa фамилия'),
            '#required' => TRUE,
        ];

        $form['subject'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Тема'),
            '#required' => TRUE,
        ];

        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Сообщение'),
            '#required' => TRUE,
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('E-mail'),
            '#description' => $this->t('Email должен быть корректным'),
            '#required' => TRUE,
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Отправить форму'),
        ];

        return $form;
    }

    public function getFormId() {
        return 'ex_form_exform_form';
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
            $form_state->setErrorByName('email', $this->t('Данный е-мейл не корректен'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        $send_mail = new PhpMail();
        $from = $form['email'];
        $to = "kurts_k@list.ru";
        $message['headers'] = [
        'content-type' => 'text/html',
        'MIME-Version' => '1.0',
        'reply-to' => $from,
        'from' => 'sender name <'.$from.'>'
        ];

        $message['to'] = $to;
        $message['subject'] = $form['subject'];
        $message['body'] = $form['message'];
        $send_mail->mail($message);

        $send_mail == true ? $res = 'Ваше письмо отправлено' : $res = 'Ваше письмо не отправлено';

        drupal_set_message($res);

    }

}

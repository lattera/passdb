<?php

require_once('classes/Account.php');

function passdb_config_account($form, &$form_state) {
    global $user;

    $account = Account::Load($form_state['build_info']['args'][0]);
    if ($account == NULL || $account->uid != $user->uid) {
        drupal_set_message(t('Access Denied'), 'error');
        return $form;
    }

    $form['basic_info'] = array(
        '#type' => 'fieldset',
        '#title' => t('Basic Info'),
    );

    $form['basic_info']['site'] = array(
        '#type' => 'textfield',
        '#title' => t('Site'),
        '#size' => 100,
        '#required' => TRUE,
        '#default_value' => $account->site,
    );

    $form['basic_info']['username'] = array(
        '#type' => 'textfield',
        '#title' => t('Username'),
        '#size' => 100,
        '#default_value' => $account->username,
    );

    $form['basic_info']['password'] = array(
        '#type' => 'password',
        '#title' => t('Password'),
        '#size' => 100,
        '#default_value' => $account->password,
    );

    $form['basic_info']['description'] = array(
        '#type' => 'textarea',
        '#title' => t('Description'),
        '#default_value' => $account->description,
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Save'),
    );

    $form['delete'] = array(
        '#type' => 'submit',
        '#value' => t('Delete'),
    );

    return $form;
}

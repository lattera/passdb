<?php

require_once('classes/Account.php');

function passdb_list($form, &$form_state) {
    global $user;

    $accounts = Account::LoadAll($user->uid);
    if (count($accounts) == 0)
        drupal_set_message(t('No accounts defined. Add an account below.'), 'error');
    else
        show_list($accounts, $form, $form_state);

    return $form;
}

function show_list($accounts, &$form, &$form_state) {
    $header = array('Site', 'Username', 'Creation Date', 'Update Date');
    $rows = array();

    foreach ($accounts as $account) {
        $rows[] = array(
            $account->site,
            $account->username,
            $account->create_timestamp,
            $account->update_timestamp
        );
    }

    $form['account_list'] = array(
        '#type' => 'markup',
        '#prefix' => '<div>',
        '#markup' => theme('table', array(
            'header' => $header,
            'rows' => $rows,
        )),
        '#suffix' => '</div>',
    );
}

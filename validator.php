<?php

    $array = array( "firstname" => '',
        "name" => '',
        "email" => '',
        "phone" => '',
        "message" => '',

        "firstnameError" => '',
        "nameError" => '',
        "emailError" => '',
        "phoneError" => '',
        "messageError" => '',

        "isSuccess" => false);

    $emailTo = "chena.faycal@gmail.com";

    if ($_SERVER["REQUEST_METHOD"] === "POST")
    {
        $array['firstname'] = verifyInput($_POST['firstname']);
        $array['name'] = verifyInput($_POST['name']);
        $array['email'] = verifyInput($_POST['email']);
        $array['phone'] = verifyInput($_POST['phone']);
        $array['message'] = verifyInput($_POST['message']);
        $array['isSuccess'] = true;
        $emailText = '';

        if (empty($array['firstname']))
        {
            $array['firstnameError'] = 'Veuillez entrer un prénom valide';
            $array['isSuccess'] = false;
        }
        else
        {
            $emailText .= "Prénom: {$array['firstname']}\n";
        }

        if (empty($array["name"]))
        {
            $array["nameError"] = "Veuillez entrer un nom valide";
            $array["isSuccess"] = false;
        }
        else
        {
            $emailText .= "Name: {$array['name']}\n";
        }

        if(!isEmail($array["email"]))
        {
            $array["emailError"] = "Veuillez entrer une adresse e-mail valide";
            $array["isSuccess"] = false;
        }
        else
        {
            $emailText .= "Email: {$array['email']}\n";
        }

        if (!isPhone($array["phone"]))
        {
            $array["phoneError"] = "Veuillez entrer votre numéro de téléphone au bon format";
            $array["isSuccess"] = false;
        }
        else
        {
            $emailText .= "Téléphone: {$array['phone']}\n";
        }

        if (empty($array["message"]))
        {
            $array["messageError"] = "Ce champ est obligatoire";
            $array["isSuccess"] = false;
        }
        else
        {
            $emailText .= "Message: {$array['message']}\n";
        }

        if($array["isSuccess"])
        {
            $headers = "From: {$array['firstname']} {$array['name']} <{$array['email']}>\r\n Reply-To: {$array['email']}";
            mail($emailTo, utf8_decode('Un message de vôtre site préferé') , utf8_decode($emailText), $headers);
        }

        echo json_encode($array);
    }

    function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function isPhone($phone)
    {
        return preg_match("/^[0-9 ]*$/",$phone);
    }

    function verifyInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


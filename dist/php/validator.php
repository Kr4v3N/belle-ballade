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

    // $emailTo = "contact@belle-balade.com";
    $emailTo = "zughiv@freeinbox.email";

    // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['g-recaptcha-response']))
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $array['firstname'] = verifyInput($_POST['firstname']);
        $array['name'] = verifyInput($_POST['name']);
        $array['email'] = verifyInput($_POST['email']);
        $array['phone'] = verifyInput($_POST['phone']);
        $array['message'] = verifyInput($_POST['message']);
        $array['isSuccess'] = true;
        $emailText = '';

        // // Build POST request:
        // $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
        // $recaptcha_secret = '6Lcw35cUAAAAAI3JBE18JL_jutsv0BwJZ-PbxyRw';
        // $recaptcha_response = $_POST['g-recaptcha-response'];

        // // Make and decode POST request:
        //  $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);

        // $recaptcha = json_decode($recaptcha);

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
            $emailText .= "Nom: {$array['name']}\n";
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

        // Take action based on the score returned:
        // if($array["isSuccess"] && $recaptcha->success == true && $recaptcha->score > 0.5)
        if ($array["isSuccess"])
        {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .='Content-Transfer-Encoding: 8bit';
            $headers .= "From: <{$array['firstname']}> {$array['name']} <{$array['email']}>\r\n Reply-To: {$array['email']}";
            $subject = "Message de votre site belle balade";
            mail($emailTo, $subject, $emailText, $headers);
        }
        // else
        // {
        //     echo '<pre>';
        //         print_r("Not verified - show form error");
        //         print_r($recaptcha);
        //     echo '</pre>';
        // }

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


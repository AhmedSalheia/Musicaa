<?php

if (isset($_POST['sub']))
{
    $file = 'file.csv';
    if (move_uploaded_file($_FILES['file']['tmp_name'],'file.txt'))
    {

        $count = 1;
        $arr = ["Name","Given Name","Additional Name","Family Name","Yomi Name","Given Name Yomi","Additional Name Yomi","Family Name Yomi","Name Prefix","Name Suffix","Initials","Nickname","Short Name","Maiden Name","Birthday","Gender","Location","Billing Information","Directory Server","Mileage","Occupation","Hobby","Sensitivity","Priority","Subject","Notes","Language","Photo","Group Membership","Phone 1 - Type","Phone 1 - Value"];
        $fp1 = fopen($file,'w+');
        fwrite($fp1,implode(',',$arr)."\n");

        $fp = fopen('file.txt','r+');
        while (($line = fgets($fp)) !== false) {
            foreach ($arr as $item)
            {
                $content = ',';
                if ($item==="Phone 1 - Value")
                {
                    $content = "+".trim(str_replace('N/A','',$line))."\n";
                }elseif ($item==="Phone 1 - Type")
                {
                    $content = "Mobile,";
                }elseif ($item==="Name")
                {
                    $content = $_POST['name'].$count++.",";
                }
               fwrite($fp1,$content);
            }
        }

        fclose($fp);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush(); // Flush system output buffer
        readfile($file);
        exit();
    }else
    {
        $err = 'Error Uploading File';
    }
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Try CSV</title>
</head>
<body>
    <form action="#" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="write the name you want for the contacts" />
        <input type="file" name="file" />
        <input type="submit" name="sub" />
    </form>
</body>
</html>
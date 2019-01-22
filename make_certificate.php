<?php
    // For generating pdf
    require('fpdf/fpdf.php');

    // Main
    if($_POST["name"] && $_FILES["photo"])
    {
        $errors = array();
        $file_name = $_FILES["photo"]["name"];
        $file_tmp = $_FILES["photo"]["tmp_name"];
        $file_type = $_FILES["photo"]["type"];
        $tmp = explode('.', $file_name);
        $file_ext = strtolower(end($tmp));
        $extensions = array("jpeg", "jpg", "png");
        $name = $_POST["name"];

        if(in_array($file_ext, $extensions) === false)
        {
            array_push($errors, "Image format not allowed, please choose a JPEG or PNG file.");
        }

        if(empty($errors))
        {
            // Move file to uploads temporarily
            move_uploaded_file($file_tmp, "uploads/" . $file_name);
            
            // Describe certificate format

            // Config (use class for advanced functionality)
            $certificate = new FPDF("Landscape", "pt", "A4");
            $certificate->AddPage();
            $certificate->SetFont("Times", "", 12);
            
            // Content
            $certificate->Image("uploads/" . $file_name, 10, 10, 100, 128);
            $certificate->Text(130, 10, $name);

            // Generation
            $certificate->Output("D", "certificate.pdf");
            
            // Delete image
            unlink("uploads/" . $file_name);

            echo "<h1> Generated certificate </h1>";
            exit();
        }
        else
        {
            foreach ($errors as $err) {
                echo "<h1>" . $err . "</h1>";
            }
        }

        
    }
    else
    {
        echo "<h1> Fill name and upload photo </h1>";
    }
?>
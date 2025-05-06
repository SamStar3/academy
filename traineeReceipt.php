<?php
    session_start();
    include("include/traineeReceipt.php"); 
?>

<!DOCTYPE html>
<html>
<head> 
    <title>Payment Receipt</title>
    <link rel="stylesheet" href="assets/css/traineeReceipt.css"> 
    <!-- jsPDF & html2canvas CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


</head>
<body>
<div class="container" id="receipt">
    <div class="header">
        <img src="assets/images/logo/logo.png" alt="Logo">
        <h3>RORIRI SOFTWARE SOLUTIONS PVT.LTD.</h3>
        <p>RORIRI IT PARK, NALLANATHAPURAM, Kalakkad, Keela Karuvelankulam, Tamil Nadu 627502</p>
        <h3>Payment Receipt</h3>
    </div>

    <table class="details">
        <tr>
            <td><strong>Course Name :</strong> <?php echo $course_name; ?></td>
            <td style="text-align: right;"><strong>Trainee Name :</strong> <?php echo $student_name; ?></td>
        </tr>
        <tr>
            <td><strong>Date :</strong> <?php echo $date; ?></td>
            <td></td>
        </tr>
    </table>

    <table class="table">
        <tr>
            <th>Description</th>
            <th>Method</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>Fees</td>
            <td><?php echo $method; ?></td>
            <td>Rs. <?php echo $amount; ?></td>
        </tr>
        <tr class="totals">
            <td colspan="2" style="text-align: right;">Total</td>
            <td>Rs. <?php echo $amount; ?></td>
        </tr>
    </table>

    <div class="footer">
        Thank you for your payment!
        <button class="download-btn" onclick="downloadPDF()">Download PDF</button>
        
    </div>  
       
</div>



<!-- JS for download and back -->
<script src="js_functions/traineeReceipt.js" type="text/javascript"></script>


</body>
</html>



function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const receipt = document.getElementById("receipt");
    const button = document.querySelector(".download-btn");

    // Hide button
    button.style.display = "none";

    html2canvas(receipt, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "mm", "a4");
        const imgWidth = 190;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        pdf.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
        pdf.save("Payment_Receipt.pdf");

        // Show button again
        button.style.display = "block";
    }).catch(error => {
        console.error("PDF Download Error:", error);
        // Re-show button if error happens
        button.style.display = "block";
    });
}


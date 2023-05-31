<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PDFHelper
{
    public static function generatePDF($html, $filename)
    {
        // Create a new Dompdf instance with options
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);

        // Load the HTML into Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Generate a unique filename
        $uniqueFilename = Str::random(16) . '.pdf';

        // Choose the desired storage disk (e.g., 'public')
        $disk = 'public';

        // Specify the storage path for the PDF file
        $storagePath = 'pdfs/' . $uniqueFilename;

        // Save the PDF to the storage disk
        Storage::disk($disk)->put($storagePath, $dompdf->output());

        // Generate the URL for accessing the PDF file
        $pdfUrl = Storage::disk($disk)->url($storagePath);

        return $pdfUrl;
    }
}

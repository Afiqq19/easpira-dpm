<?php

namespace App\Services;

use ZipArchive;

class SimpleXlsxExporter
{
    /**
     * Generate raw XLSX file content.
     *
     * @param string $sheetTitle
     * @param array $headers Column headers e.g. ['NO', 'KODE TIKET', ...]
     * @param array $rows Array of row arrays e.g. [[1, 'TKT-001', ...], ...]
     * @param array $colWidths Approximate column widths in characters e.g. [8, 18, 25, ...]
     * @param string|null $mainTitle Optional big title at top e.g. "LAPORAN DATA PENGADUAN MAHASISWA"
     * @param string|null $subTitle Optional sub title e.g. "Diunduh pada: 23/09/2026 06:30 WIB"
     * @return string Raw binary string of the XLSX zip file
     */
    public static function create(
        string $sheetTitle,
        array $headers,
        array $rows,
        array $colWidths = [],
        ?string $mainTitle = null,
        ?string $subTitle = null
    ): string {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive();

        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Cannot create temporary zip file for XLSX.");
        }

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $safeSheetTitle = htmlspecialchars(mb_substr($sheetTitle, 0, 31), ENT_XML1);
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>'
            . '<sheet name="' . $safeSheetTitle . '" sheetId="1" r:id="rId1"/>'
            . '</sheets>'
            . '</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 5. xl/styles.xml
        // Style IDs (s attribute):
        // 0: Normal
        // 1: Big Title (Bold 14pt, Navy #1E3A8A)
        // 2: Subtitle (Italic 10pt, Gray #64748B)
        // 3: Table Header (Bold 11pt, White #FFFFFF, Fill Navy #1E3A8A, Thin Border, Center)
        // 4: Table Header Left (Bold 11pt, White #FFFFFF, Fill Navy #1E3A8A, Thin Border, Left)
        // 5: Cell Normal (10pt, Thin Border, Left)
        // 6: Cell Center (10pt, Thin Border, Center)
        // 7: Cell Alt Row (10pt, Fill #F8FAFC, Thin Border, Left)
        // 8: Cell Alt Center (10pt, Fill #F8FAFC, Thin Border, Center)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="5">'
            . '<font><sz val="10"/><color rgb="FF1E293B"/><name val="Calibri"/></font>' // 0: normal
            . '<font><b/><sz val="14"/><color rgb="FF1E3A8A"/><name val="Calibri"/></font>' // 1: title
            . '<font><i/><sz val="9"/><color rgb="FF64748B"/><name val="Calibri"/></font>' // 2: subtitle
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>' // 3: header
            . '<font><b/><sz val="10"/><color rgb="FF0F172A"/><name val="Calibri"/></font>' // 4: bold data
            . '</fonts>'
            . '<fills count="4">'
            . '<fill><patternFill patternType="none"/></fill>' // 0: none
            . '<fill><patternFill patternType="gray125"/></fill>' // 1: gray125
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF1E3A8A"/></patternFill></fill>' // 2: header navy
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFF1F5F9"/></patternFill></fill>' // 3: alternate row soft slate
            . '</fills>'
            . '<borders count="2">'
            . '<border><left/><right/><top/><bottom/><diagonal/></border>' // 0: none
            . '<border>'
            . '<left style="thin"><color rgb="FFCBD5E1"/></left>'
            . '<right style="thin"><color rgb="FFCBD5E1"/></right>'
            . '<top style="thin"><color rgb="FFCBD5E1"/></top>'
            . '<bottom style="thin"><color rgb="FFCBD5E1"/></bottom>'
            . '</border>' // 1: thin slate border
            . '</borders>'
            . '<cellXfs count="9">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>' // 0: normal
            . '<xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1"><alignment vertical="center"/></xf>' // 1: title
            . '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1"><alignment vertical="center"/></xf>' // 2: subtitle
            . '<xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>' // 3: header center
            . '<xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="left" vertical="center" wrapText="1"/></xf>' // 4: header left
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="left" vertical="center" wrapText="1"/></xf>' // 5: cell left
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 6: cell center
            . '<xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="left" vertical="center" wrapText="1"/></xf>' // 7: cell alt left
            . '<xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"><alignment horizontal="center" vertical="center"/></xf>' // 8: cell alt center
            . '</cellXfs>'
            . '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView tabSelected="1" workbookViewId="0"/></sheetViews>'
            . '<sheetFormatPr defaultRowHeight="20"/>';

        // Column widths
        if (!empty($colWidths)) {
            $sheetXml .= '<cols>';
            foreach ($colWidths as $idx => $width) {
                $colNum = $idx + 1;
                $sheetXml .= '<col min="' . $colNum . '" max="' . $colNum . '" width="' . (float) $width . '" customWidth="1"/>';
            }
            $sheetXml .= '</cols>';
        }

        $sheetXml .= '<sheetData>';
        $rowIndex = 1;

        // Main Title
        if (!empty($mainTitle)) {
            $sheetXml .= '<row r="' . $rowIndex . '" ht="26" customHeight="1">';
            $sheetXml .= '<c r="A' . $rowIndex . '" s="1" t="inlineStr"><is><t>' . htmlspecialchars($mainTitle, ENT_XML1) . '</t></is></c>';
            $sheetXml .= '</row>';
            $rowIndex++;
        }

        // Subtitle
        if (!empty($subTitle)) {
            $sheetXml .= '<row r="' . $rowIndex . '" ht="18" customHeight="1">';
            $sheetXml .= '<c r="A' . $rowIndex . '" s="2" t="inlineStr"><is><t>' . htmlspecialchars($subTitle, ENT_XML1) . '</t></is></c>';
            $sheetXml .= '</row>';
            $rowIndex++;
        }

        // Blank line before headers if there's a title
        if (!empty($mainTitle)) {
            $rowIndex++;
        }

        // Headers row
        $sheetXml .= '<row r="' . $rowIndex . '" ht="28" customHeight="1">';
        foreach ($headers as $cIdx => $headerText) {
            $colLetter = self::colIndexToLetter($cIdx);
            $cellRef = $colLetter . $rowIndex;
            // First column or status/date can be center, text left
            $styleId = in_array(mb_strtoupper((string) $headerText), ['NO', 'TANGGAL', 'STATUS', 'PRIORITAS', 'KODE TIKET']) ? 3 : 4;
            $sheetXml .= '<c r="' . $cellRef . '" s="' . $styleId . '" t="inlineStr"><is><t>' . htmlspecialchars(mb_strtoupper((string) $headerText), ENT_XML1) . '</t></is></c>';
        }
        $sheetXml .= '</row>';
        $rowIndex++;

        // Data rows
        foreach ($rows as $rIdx => $rowValues) {
            $isAlt = ($rIdx % 2 === 1);
            $sheetXml .= '<row r="' . $rowIndex . '" ht="22" customHeight="1">';
            foreach ($rowValues as $cIdx => $val) {
                $colLetter = self::colIndexToLetter($cIdx);
                $cellRef = $colLetter . $rowIndex;
                $valStr = (string) ($val ?? '');

                // Align center for NO, Date, Ticket Code, Status
                $headerName = isset($headers[$cIdx]) ? mb_strtoupper((string) $headers[$cIdx]) : '';
                $isCenter = in_array($headerName, ['NO', 'TANGGAL', 'STATUS', 'PRIORITAS', 'KODE TIKET', 'TANGGAL PENGADUAN', 'TANGGAL DITANGANI']);

                $cellStyle = $isCenter ? ($isAlt ? 8 : 6) : ($isAlt ? 7 : 5);

                $sheetXml .= '<c r="' . $cellRef . '" s="' . $cellStyle . '" t="inlineStr"><is><t>' . htmlspecialchars($valStr, ENT_XML1) . '</t></is></c>';
            }
            $sheetXml .= '</row>';
            $rowIndex++;
        }

        $sheetXml .= '</sheetData>';
        $sheetXml .= '</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();

        $content = file_get_contents($tempFile);
        @unlink($tempFile);

        return $content;
    }

    /**
     * Convert 0-indexed column index to Excel column letter (0 => A, 25 => Z, 26 => AA, etc.)
     */
    private static function colIndexToLetter(int $index): string
    {
        $letter = '';
        $index++;
        while ($index > 0) {
            $remainder = ($index - 1) % 26;
            $letter = chr(65 + $remainder) . $letter;
            $index = intdiv($index - $remainder - 1, 26);
        }
        return $letter;
    }
}

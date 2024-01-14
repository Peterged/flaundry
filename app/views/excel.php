<?php
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=student_list.xlsx");
header("Pragma: no-cache");
header("Expires: 0");


echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
        <head>
            <!--[if gte mso 9]>
            <xml>
                <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                        <x:ExcelWorksheet>
                            <x:Name>Sheet1</x:Name>
                            <x:WorksheetOptions>
                                <x:DisplayGridlines/>
                            </x:WorksheetOptions>
                        </x:ExcelWorksheet>
                    </x:ExcelWorksheets>
                </x:ExcelWorkbook>
            </xml>
            <![endif]-->
        </head>
        <body>';

echo '<table border="1">
        <tr>
            <th style="background-color: #f2f2f2; font-weight: bold;">Name</th>
            <th style="background-color: #f2f2f2; font-weight: bold;">Email</th>
            <th style="background-color: #f2f2f2; font-weight: bold;">Phone</th>
        </tr>
        <tr>
            <td>John Doe</td>
            <td>john@example.com</td>
            <td>123-456-7890</td>
        </tr>
        <tr>
            <td>Jane Smith</td>
            <td>jane@example.com</td>
            <td>987-654-3210</td>
        </tr>
    </table>';

echo '</body></html>';
exit;

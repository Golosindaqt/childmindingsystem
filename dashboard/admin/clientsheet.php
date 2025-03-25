<?php
include '../../db_conn.php';

// Pagination settings
$limit = 20; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query to count the total number of records
$count_query = "
    SELECT COUNT(*) as total FROM enrollment e
    INNER JOIN parental_information p ON e.child_id = p.child_id
    INNER JOIN child_record cr ON cr.child_id = p.child_id
    WHERE e.enrollment_status = 'accepted'
";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $limit);

// Query to fetch the records for the current page
$query = "
    SELECT e.*, p.*, cr.*  
    FROM enrollment e
    INNER JOIN parental_information p ON e.child_id = p.child_id
    INNER JOIN child_record cr ON cr.child_id = p.child_id
    WHERE e.enrollment_status = 'accepted'
    ORDER BY e.enrollment_id DESC
    LIMIT $start, $limit
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("head.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

    <style type="text/css">
        .dropdown-menu a:hover { background:#035392;color:white }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #035392;
        }

        .pagination a:hover {
            background-color: #035392;
            color: white;
        }

        .pagination .active {
            background-color: #035392;
            color: white;
        }
    </style>
</head>
<body class="sidebar-expand counter-scroll">
    <?php include("leftsidebar.php"); ?>
    <?php include("header.php"); ?>

    <div class="main">
        <div class="main-content project">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="box ">
                        <div class="box-header pt-0">
                            <div class="" style="display: flex;flex-wrap:wrap;justify-content: space-between;width: 100%;">
                                <h4 class="card-title mb-0 fs-22">Clients'&nbsp;Sheet<br><span style="font-size: 15px;color: #035392" id="totalfound"><?php echo $total_records; ?> Found</span>
                                <span class="gr-btn mt-15" style="display: flex;justify-content: flex-start;">
                                    <button type="button" class="btn btn-primary btn-lg" id="download-pdf-btn" style="font-size: 12px;margin-top: 0px;margin-left: 0px;padding:10">
                                        <i class='bx bx-download'></i> Download PDF
                                    </button>
                                    <button type="button" class="btn btn-success btn-lg" id="download-excel-btn" style="font-size: 12px;margin-top: 0px;margin-left: 10px;padding:10">
                                        <i class='bx bx-download'></i> Download Excel
                                    </button>
                                </span>
                                </h4>
                                <input type="text" id="searchInput" placeholder="Search Here..." class="form-control" style="width: 200px; height: 50px;" onkeyup="filterTable()">
                            </div>
                        </div>
                        <div class="box-body pb-0 table-responsive activity mt-18">
                            <?php
                                if ($result) {
                                    if (mysqli_num_rows($result) > 0) {
                                        echo '<div id="enrollments-download"><img src="header_clientsheet.png" style="width:125%; height: 200px;"><table class="table table-vcenter text-nowrap table-bordered dataTable no-footer mw-100" id="enrollments" role="grid">';
                                        echo '<thead>';
                                        echo '<tr class="top" style="background: #035392;color:white">';
                                        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Client\'s Profile</th>';
                                        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Gender</th>';
                                        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Age</th>';
                                        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Parent\'s Name (USTP Employee)</th>';
                                        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Employment Status</th>';
                                        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Entrance Date</th>';
                                        echo '<th class="border-bottom-0 sorting fs-14 font-w500">Commencement Date</th>';
                                        echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<tr>';
                                            echo '<form method="post" action="view_enrollment">
                                                <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
                                                <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['child_name']) . '</button></td>
                                            </form>';
                                            echo '<form method="post" action="view_enrollment">
                                                <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
                                                <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['gender']) . '</button></td>
                                            </form>';
                                            echo '<form method="post" action="view_enrollment">
                                                <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
                                                <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['child_age']) . '</button></td>
                                            </form>';
                                            echo '<form method="post" action="view_enrollment">
                                                <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
                                                <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['mother_name']) . ' <br> ' . htmlspecialchars($row['father_name']) . '</button></td>
                                            </form>';
                                            echo '<form method="post" action="view_enrollment">
                                                <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
                                                <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . htmlspecialchars($row['released_status']) . '</button></td>
                                            </form>';
                                            echo '<form method="post" action="view_enrollment">
                                                <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
                                                <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . date("F j, Y", strtotime($row['parental_agreement_start_month'])) . '</button></td>
                                            </form>';
                                            echo '<form method="post" action="view_enrollment">
                                                <input type="hidden" name="ref" value="' . htmlspecialchars($row['ref']) . '">
                                                <td><button type="submit" class="btn btn-link text-decoration-none" style="color:black">' . date("F j, Y", strtotime($row['parental_agreement_end_month'])) . '</button></td>
                                            </form>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                        echo '</table></div>';
                                    } else {
                                        echo '<p>No data found.</p>';
                                    }
                                } else {
                                    echo 'Error: ' . mysqli_error($conn);
                                }
                                mysqli_close($conn);
                            ?>
                            
                            <!-- Pagination -->
                            <div class="pagination">
                                <?php
                                // Previous page link
                                if ($page > 1) {
                                    echo '<a href="?page=' . ($page - 1) . '">Prev</a>';
                                }

                                // Page links
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    if ($i == $page) {
                                        echo '<span class="active">' . $i . '</span>';
                                    } else {
                                        echo '<a href="?page=' . $i . '">' . $i . '</a>';
                                    }
                                }

                                // Next page link
                                if ($page < $total_pages) {
                                    echo '<a href="?page=' . ($page + 1) . '">Next</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay"></div>

    <script>
        // PDF download functionality
        function ensureImagesLoaded(element) {
            const images = element.querySelectorAll('img');
            const promises = Array.from(images).map(img => {
                return new Promise(resolve => {
                    if (img.complete) {
                        resolve();
                    } else {
                        img.onload = resolve;
                        img.onerror = resolve; // Resolve even if image fails to load
                    }
                });
            });
            return Promise.all(promises);
        }

        document.getElementById('download-pdf-btn').addEventListener('click', async function () {
            const element = document.getElementById('enrollments-download');
            await ensureImagesLoaded(element); // Ensure all images are loaded

            const options = {
                margin: 0.5,
                filename: 'Client Sheet Report.pdf',
                image: { type: 'png', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    letterRendering: true,
                    width: element.scrollWidth,
                    height: element.scrollHeight,
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'landscape',
                    compress: true,
                }
            };
            html2pdf().set(options).from(element).save();
        });

        // Excel download functionality
        document.getElementById('download-excel-btn').addEventListener('click', function () {
            const table = document.getElementById('enrollments');
            const rows = table.querySelectorAll('tr');
            const data = [];
            const headers = ['Client\'s Profile', 'Gender', 'Age', 'Parent\'s Name (USTP Employee)', 'Employment Status', 'Entrance Date', 'Commencement Date'];

            // Adding headers
            data.push(headers);

            // Adding rows
            rows.forEach((row, index) => {
                if (index > 0) { // skip the header row
                    const cols = row.querySelectorAll('td');
                    const rowData = [];
                    rowData.push(cols[0].innerText.trim());
                    rowData.push(cols[1].innerText.trim());
                    rowData.push(cols[2].innerText.trim());
                    rowData.push(cols[3].innerText.trim());
                    rowData.push(cols[4].innerText.trim());
                    rowData.push(cols[5].innerText.trim());
                    rowData.push(cols[6].innerText.trim());
                    data.push(rowData);
                }
            });

            // Creating the Excel file
            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Clients');
            XLSX.writeFile(wb, 'Client_Sheet_Report.xlsx');
        });

        // Table filter functionality
        function filterTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('enrollments');
            const rows = table.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j]) {
                        if (cells[j].textContent.toLowerCase().includes(filter)) {
                            match = true;
                            break;
                        }
                    }
                }
                rows[i].style.display = match ? '' : 'none';
            }
        }
    </script>
    
    <script src="../libs/jquery/jquery.min.js"></script>
        <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../libs/owl.carousel/owl.carousel.min.js"></script>
        <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
        <script src="../libs/apexcharts/apexcharts.js"></script>
        <script src="../js/main.js"></script>
        <script src="../js/shortcode.js"></script>
        <script src="../js/pages/dashboard.js"></script>
</body>
</html>

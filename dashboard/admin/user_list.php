<?php
    include '../../db_conn.php';

    if (isset($_SESSION['teacher_logged_email'])) {
        $teacherEmail = $_SESSION['teacher_logged_email'];
        $teacherUserId = $_SESSION['teacher_logged_user_id'];
        $teacherId = $_SESSION['teacher_logged_teacher_id'];
        $teacherUsername = $_SESSION['teacher_logged_username'];
        
        $query = "
            SELECT u.username, u.email, p.mother_name, p.father_name, u.user_id, p.*
            FROM user u
            INNER JOIN parental_information p ON u.email = p.email
            WHERE u.role_id = 2 
              AND u.username IS NOT NULL 
              AND u.username <> ''
        ";

        $result = mysqli_query($conn, $query);
        if (!$result) {
            die('Query failed: ' . mysqli_error($conn));
        }

        $uniqueResults = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if (!isset($uniqueResults[$row['email']])) {
                $uniqueResults[$row['email']] = $row;
            }
        }

        foreach ($uniqueResults as $row):
            $userId = $row['user_id'];
            $unseenQuery = "
                SELECT COUNT(*) AS unseen_count 
                FROM notification 
                WHERE type = 'msg' 
                  AND teacher_id = '$teacherUserId' 
                  AND teacherseen = 'no'
                  AND user_id = '$userId'
            ";

            $unseenResult = mysqli_query($conn, $unseenQuery);
            if (!$unseenResult) {
                die('Query failed: ' . mysqli_error($conn));
            }

            $unseenCountRow = mysqli_fetch_assoc($unseenResult);
            $unseenCount = $unseenCountRow['unseen_count'];
    ?>
        <li class="waves-effect waves-teal user-item" style="margin-top: 10px;width: 100%" 
            data-username="<?php echo htmlspecialchars($row['username']); ?>" 
            data-mothername="<?php echo htmlspecialchars($row['mother_name']); ?>" 
            data-fathername="<?php echo htmlspecialchars($row['father_name']); ?>" 
            data-email="<?php echo htmlspecialchars($row['email']); ?>">
            <div class="left d-flex">

               <?php if ($unseenCount > 0): ?>
                <div style="position: absolute;right: 10px; top: 10px; text-align: center;padding-top: 1px;color:white; background: red;height: 25px;width: 25px;border-radius: 100%;">
                 <?php echo ($unseenCount > 0) ? "" . $unseenCount : "0"; ?>
             </div>
                 <?php endif; ?>

                <div class="avatar">
                    <img src="../../img/favicon.png" alt="">
                </div>
                <div class="content">
                    <div class="username">
                        <div class="name h6">
                            <div class="parent_user_id" style="position:absolute;left: -5000000000000px">
                                <?php echo htmlspecialchars($row['user_id']); ?>
                            </div>
                            <div class="mothernamediv"><?php echo htmlspecialchars($row['mother_name']); ?></div>
                            <div class="fathernamediv"><?php echo htmlspecialchars($row['father_name']); ?></div>
                          
                        </div>
                    </div>
                    <div class="text">
                        <p><?php echo htmlspecialchars($row['email']); ?></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </li>
    <?php endforeach; ?>
    <?php
    }
    ?>
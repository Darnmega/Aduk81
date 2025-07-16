<?php require_once("includes/layout.php"); 
  $class_infos = getMyClass($conn, $UserID); ?>
<head>
    <title>Aduk8 | Subject Information</title>
   
</head>
<body>
    <div class="container">
        <main class="main-content">
        <?php if (isset($class_infos['message'])){?>
                <div id="responseMessage" class="error">
                    <?= " Note that some features will not be available as you are not enrolled under any class";?>
                </div>
        <?php }?>
            <div class="content">
                <h2>Main Content</h2>
                <p>This is the main content area that adjusts when the sidebar expands/collapses.</p>
            </div>
        </main>
    </div>
</body>
</html>
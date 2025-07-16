<?php require_once("includes/layout.php"); 
$subject_info = getMySubjects($conn, $center_no, $UserID);
$class_infos = getMyClass($conn, $UserID);
?>
<head>
    <title>Aduk8 | Dashboard</title>
    <style>
    .subjets-container {
        background-color: var(--bg-primary);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px var(--shadow-color);
        transition: all var(--transition-speed) var(--transition-easing);
        margin-top: 20px;
        width: 100%;
        height:fit-content;
        position: relative;
        text-align: center;
    }
    
    .subjets-container h4 {
        margin-bottom: 15px;
    }
    
    .Subjects-wrapper {
        position: relative;
        width: 100%;
        overflow: hidden;
        display: grid;
        grid: center;
        align-content: center;
    }
    
    .Subjects {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding-bottom: 10px;
        -ms-overflow-style: none;
        scrollbar-width: none;

    }
    .Subjects a {
        text-decoration: none;

    }
    .Subjects::-webkit-scrollbar {
        display: none;
    }
    
    .subject {
        min-width: 200px;
        height: 100px;
        background-color: var(--accent-color);
        border-radius: 8px;
        padding: 15px;
        color: white;
        display: grid;
        text-align: center;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    /* .subject h5, p{
        display: block;
    } */
    
    .subject:hover {
        cursor: pointer;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .buttons-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
        padding-top: 10px;
    }
    
    .scroll-btn {
        width: 40px;
        height: 40px;
        background-color: var(--bg-primary);
        border: 2px solid var(--text-secondary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--text-primary);
    }
    
    .scroll-btn:hover {
        background-color: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
    }
    
    .scroll-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: var(--bg-primary);
        color: var(--text-secondary);
        border-color: var(--text-secondary);
    }
    
    .scroll-btn:disabled:hover {
        background-color: var(--bg-primary);
        color: var(--text-secondary);
        border-color: var(--text-secondary);
    }
    
    @media (max-width: 768px) {
        .subject {
            min-width: 150px;
            height: 100px;
            padding: 10px;
        }
        
        .scroll-btn {
            width: 35px;
            height: 35px;
        }
    }
    
    @media (max-width: 480px) {
        .subject {
            min-width: 120px;
            height: 80px;
            padding: 8px;
        }
        
        .scroll-btn {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }
    }

    </style>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <?php if (isset($class_infos['message'])){?>
                <div id="responseMessage" class="error">
                    <?= $class_infos['message']." Note that some features will not be available";?>
                </div>
        <?php }if (isset($subject_info['message'])) {?>
                <div id="responseMessage" class="error">
                    <?= htmlspecialchars($subject_info['message']); ?>
                </div>
            <?php } else{?>
            <div class="subjets-container">
                <h4>My Subjects</h4>
                <div class="Subjects-wrapper">
                    <div class="Subjects">
                        <?php // Display all registered subjects
                            foreach ($subject_info as $subj_row) { ?>
                                <a href="subjects/subject-info.php?subj=<?= htmlspecialchars($subj_row['subject_code']); ?>"><div class="subject">
                                    <h5><?= htmlspecialchars($subj_row['subject_name']); ?></h5>
                                    <p><?= htmlspecialchars($subj_row['classification']); ?></p>
                                </div>
                                </a>
                            <?php }
                        ?>  
                    </div>
                </div>
                <div class="buttons-container">
                    <button class="scroll-btn left" aria-label="Scroll left" disabled><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                    <button class="scroll-btn right" aria-label="Scroll right"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </div><br><?php } ?>
            <div class="content">
                <h2>Main Content</h2>
                <p>This is the main content area that adjusts when the sidebar expands/collapses.</p>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectsContainer = document.querySelector('.Subjects');
            const scrollLeftBtn = document.querySelector('.scroll-btn.left');
            const scrollRightBtn = document.querySelector('.scroll-btn.right');
            
            // Initial check
            checkScrollButtons();
            
            // Debounce resize events for better performance
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(checkScrollButtons, 100);
            });
            
            // Scroll buttons functionality
            scrollLeftBtn.addEventListener('click', () => {
                subjectsContainer.scrollBy({
                    left: -subjectsContainer.offsetWidth * 0.8,
                    behavior: 'smooth'
                });
            });
            
            scrollRightBtn.addEventListener('click', () => {
                subjectsContainer.scrollBy({
                    left: subjectsContainer.offsetWidth * 0.8,
                    behavior: 'smooth'
                });
            });
            
            // Update button visibility when scrolling
            subjectsContainer.addEventListener('scroll', checkScrollButtons);
            
            function checkScrollButtons() {
                const scrollLeft = subjectsContainer.scrollLeft;
                const maxScroll = subjectsContainer.scrollWidth - subjectsContainer.clientWidth;
                
                // Left button state
                scrollLeftBtn.disabled = scrollLeft <= 0;
                
                // Right button state
                scrollRightBtn.disabled = scrollLeft >= maxScroll - 1;
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <?php include_once(COMMON . 'references.php');
          include_once(COMMON . 'references-menu-js.php');
          include_once(COMMON . 'references-datatable-js.php'); ?>

    <style>
        table.fixedHeader-floating {
            display: none;
        }
        .w-0 {
            position: absolute;
            color: white;
            width: 2%;
            border: none;
            z-index: -10;
        }
        .modal-header {
            cursor: move;
        }
    </style>
    <script type="text/javascript">
        preLoading();
    </script>
</head>

<body class="body body-lighten view" id="body">
    <div class="loader"></div>

    <div class="d-flex" id="wrapper">
        <!-- sidebar -->
        <div class="sidebar sidebar-lighten">
            <!-- sidebar menu -->
            <?php echo $result; ?>
        </div>

        <!-- website content -->
        <div class="content">
            <!-- navbar top fixed -->
            <nav class="navbar navbar-expand-lg fixed-top navbar-lighten">
                <!-- navbar title -->
                <a class="navbar-brand navbar-link context-menu logoText bg-white"><em>Tech Menu</em></a>
                <!-- navbar sidebar menu toggle -->
                <span class="navbar-text">
                    <a href="#" id="sidebar-toggle" class="navbar-bars" onclick="return false;">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>
                </span>
            </nav>

            <!-- content container -->
            <div class="container-fluid">
                <div id="iframeContent" class="d-none">
                    <?php include_once(PAGES . 'menu_content.php'); ?>
                </div> 
                <div id="webContent" class="rounded">
                    <?php include_once(LIB_DIR . 'pageloader.php'); ?>
                </div>   
            </div>
        </div>
    </div>
    <?php include_once(COMMON . 'references-js.php'); ?>
</body>
</html>
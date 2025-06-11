<?php
   require_once __DIR__ . '/../_inc/functions.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Festava Live - Ticket HTML Form</title>
     
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">      
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link href="css/templatemo-festava-live.css" rel="stylesheet">

    </head>
    
    <body>

        <main>

            <header class="site-header">
                <div class="container">
                    <div class="row">
                        
                        <div class="col-lg-12 col-12 d-flex flex-wrap">
                            <p class="d-flex me-4 mb-0">
                                <i class="bi-person custom-icon me-2"></i>
                                <strong class="text-dark">Welcome to Music Festival 2023</strong>
                            </p>
                        </div>

                    </div>
                </div>
            </header>


            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="index.php">
                        Festava Live
                    </a>

                    <a href="ticket.php" class="btn custom-btn d-lg-none ms-auto me-4">Buy Ticket</a>
    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav align-items-lg-center ms-auto me-lg-5">
                        <?php
                            $menu_items = array(   //создаю словарь
                                'Home' => 'index.php#section_1',   //HOME ключ а индексы значение
                                'About' => 'index.php#section_2',
                                'Artists' => 'index.php#section_3',
                                'Schedule' => 'index.php#section_4',
                                'Pricing' => 'index.php#section_5',
                                'Contact' => 'index.php#section_6'
                            );
                            $menu = new MenuGenerator($menu_items);   //создаю обьек
                            echo $menu->getNavMenu();  //вывожу   print(menu.getNavMenu())    System.out.println((String) menu.getNavMenu());
                            //стрелка это ссылка на метод обьекта
                        ?>

                    </ul>

                        <a href="ticket.php" class="btn custom-btn d-lg-block d-none">Buy Ticket</a>
                    </div>
                </div>
            </nav>
<!doctype html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Custom Style -->
    <style>
        body {
            background-color: #f4f4f4;
        }
        .btn-primary {
            background-color: #191970;
            border-color: #191970;
        }
        .btn-primary:hover {
            background-color: #000080;
            border-color: #000080;
        }
        .btn-success {
            background-color: #006400;
            border-color: #006400;
        }
        .btn-success:hover {
            background-color: #004d00;
            border-color: #004d00;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-sm navbar-light bg-info">
                <a class="navbar-brand" href="#">SuperMarket</a>
                <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse"
                        data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="/">Accueil <i class="fa fa-home" aria-hidden="true"></i> <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="text" placeholder="Rechercher">
                        <button class="btn btn-success my-2 my-sm-0" type="submit">Rechercher</button>
                    </form>
                </div>
            </nav>
        </div>

        <div class="col-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="card-title">@yield('titreContenu')</h4>
                    <p class="card-text">@yield('sousTitreContenu')</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @yield('contenu')
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="d-flex justify-content-center mt-4">
    <i class="fa fa-copyright" aria-hidden="true"></i> FOFANA {{ date('Y') }} - Tous droits réservés
</footer>

<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>

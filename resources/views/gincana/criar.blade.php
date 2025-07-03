<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Gincana</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        body { margin: 0; font-family: sans-serif; }
        header, footer {
            background-color: #f1f1f1;
            padding: 10px 20px;
            text-align: center;
            font-weight: bold;
        }

        .container {
            padding: 1rem;
            text-align: center;
        }

        #street-view {
            width: 100%;
            height: 70vh;
            background-color: #eee;
        }

        button {
            margin-top: 1rem;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<header>Criar Nova Gincana</header>

<div class="container">
    <p>Escolha um local no Street View para a gincana</p>
    <div id="street-view"></div>
    <button onclick="salvarGincana()">Salvar Gincana</button>
</div>

<footer>
    © 2025 Gincaneiros
</footer>

<!-- Google Maps JS API + Street View -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzEzusC_k3oEoPnqynq2N4a0aA3arzH-c"></script>
<script>
    let panorama;
    let selectedLatLng;

    function initStreetView() {
        const defaultLatLng = { lat: -23.55052, lng: -46.63331 }; // São Paulo, por exemplo
        panorama = new google.maps.StreetViewPanorama(
            document.getElementById("street-view"),
            {
                position: defaultLatLng,
                pov: { heading: 165, pitch: 0 },
                zoom: 1
            }
        );

        panorama.addListener("position_changed", () => {
            selectedLatLng = panorama.getPosition().toJSON();
            console.log("Posição selecionada:", selectedLatLng);
        });
    }

    function salvarGincana() {
        if (!selectedLatLng) {
            alert("Escolha um local no Street View antes de salvar.");
            return;
        }

        // Aqui você pode enviar via fetch ou AJAX para o Laravel
        console.log("Enviando gincana com:", selectedLatLng);
        alert("Simulação de envio da gincana. Integração com Firebase/MySQL virá na próxima etapa.");
    }

    window.onload = initStreetView;
</script>

</body>
</html>

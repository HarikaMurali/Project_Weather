<?php
// Your OpenWeatherMap API Key
$apiKey = '5bd5642b1bf324972cf31912646aa37c'; // Replace with your API key
$city = ' '; // Default city is blank

// Check if the user submitted a new city
if (isset($_GET['city'])) {
    $city = htmlspecialchars($_GET['city']);
}

// Only fetch weather data if a city is provided
if ($city) {
    // Weather API endpoint
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

    // Fetch the weather data
    $response = @file_get_contents($apiUrl); // Suppress errors with @
    $data = $response ? json_decode($response, true) : null;

    // Check if the data retrieval is successful
    if ($data && $data['cod'] === 200) {
        // Extract relevant data from the response
        $temperature = $data['main']['temp'];
        $humidity = $data['main']['humidity'];
        $windSpeed = $data['wind']['speed'];
        $weatherCondition = $data['weather'][0]['description'];
        $icon = $data['weather'][0]['icon'];
        $cityName = $data['name'];
        $country = $data['sys']['country'];

        // Define a mapping of weather conditions to custom image paths
        $conditionImages = [
            'clear sky' => 'https://tse4.mm.bing.net/th?id=OIP.1BNXPq-fvc31m6BjtcPBngHaE8&pid=Api&P=0&h=180',
            'few clouds' => 'https://img.freepik.com/premium-photo/beautiful-day-with-few-clouds-sky_14117-527997.jpg',
            'scattered clouds' => 'https://media.istockphoto.com/id/1028827352/photo/sky.jpg?s=612x612&w=0&k=20&c=IphiZJlyetOs3RuzTjBmc9gRGCavGvmdk20qFTNJX8A=',
            'broken clouds' => 'https://freerangestock.com/sample/119740/broken-white-clouds-over-blue-sky.jpg',
            'shower rain' => 'https://static.vecteezy.com/system/resources/thumbnails/042/146/565/small_2x/ai-generated-beautiful-rain-day-view-photo.jpg',
            'rain' => 'https://images4.alphacoders.com/279/279306.jpg',
            'thunderstorm' => 'https://media.istockphoto.com/id/1318748572/photo/massive-lightning-strike-over-the-brisbane-city-suburbs-lights.jpg?s=612x612&w=0&k=20&c=9Z5tynrQYH3E0fruCBlwIbgsgbdu5_DHxLbSu44o3co=',
            'snow' => 'https://images.pexels.com/photos/688660/pexels-photo-688660.jpeg',
            'mist' => 'https://wallpaperaccess.com/full/2629855.jpg',
            'haze' => 'https://tse2.mm.bing.net/th?id=OIP.k8st7oyP_NGg2TlItD0DLwHaEK&pid=Api&P=0&h=180',
            'overcast clouds' => 'https://images.pexels.com/photos/8264289/pexels-photo-8264289.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
            'little rain' => 'https://tse1.mm.bing.net/th?id=OIP.kK59WAmsFs9RcEWwO9MuZwHaEK&pid=Api&P=0&h=180',
        ];

        // Default to a generic image if the condition is not in the map
        $weatherImage = $conditionImages[$weatherCondition] ?? 'https://t4.ftcdn.net/jpg/07/24/00/11/360_F_724001193_eKGDuOZwvJoLt2zYHQQuFyyf2kQh7qEP.jpg';
    } else {
        $error = "City not found or invalid input!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <style>
        /* General Reset */
        * {
            margin: 10;
            padding: 10;
            box-sizing: border-box;
        }

        body {
            body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3); /* Semi-transparent black overlay */
    z-index: -1; /* Ensures the overlay stays behind content */
}

@keyframes animateBackground {
    0% {
        background-position: center top;
    }
    50% {
        background-position: center center;
    }
    100% {
        background-position: center bottom;
    }
}
            font-family: Arial, sans-serif;
            background-color: hwb(250 9% 33%);
            color: #FFFFFF;
            text-align: center;
            padding: 20px;
            background-image: url('https://wallpapercave.com/wp/qQa5Pd7.jpg'); /* Dynamic background */
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            animation: animateBackground 20s infinite alternate; /* Smooth animation */
        }

        @keyframes animateBackground {
            0% {
                background-position: center top;
            }
            50% {
                background-position: center center;
            }
            100% {
                background-position: center bottom;
            }
        }

        .weather-form {
            margin-bottom: 20px;
        }

        .weather-form input {
            padding: 10px;
            width: 250px;
            font-size: 1.1em;
            margin-right: 10px;
        }

        .weather-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .weather-form button:hover {
            background-color: #45a049;
        }

        .weather-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 10px;
            overflow: hidden;
        }

        .weather-image {
            width: 100%;
            height: auto;
        }

        .weather-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .weather-overlay h2, .weather-overlay p {
            margin: 5px 0;
        }

        .weather-overlay h2 {
            font-size: 2em;
            font-weight: bold;
        }

        .weather-overlay p {
            font-size: 1.2em;
        }

        .error {
            color: red;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1>WEATHER WHISPERS</h1>
    <form class="weather-form" method="get">
        <input type="text" name="city" placeholder="Enter city name" required>
        <button type="submit">Get Weather</button>
    </form>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php elseif ($city && isset($data)): ?>
        <div class="weather-container">
            <img class="weather-image" src="<?php echo $weatherImage; ?>" alt="Weather Background">
            <div class="weather-overlay">
                <h2><?php echo "{$cityName}, {$country}"; ?></h2>
                <p>Temperature: <?php echo $temperature; ?>°C</p>
                <p>Humidity: <?php echo $humidity; ?>%</p>
                <p>Wind Speed: <?php echo $windSpeed; ?> m/s</p>
                <p>Condition: <?php echo ucfirst($weatherCondition); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 2s ease-in-out';
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
</body>
</html>
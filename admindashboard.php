<?php
// Connect to DB
$conn = new mysqli("localhost", "root", "", "animal_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);



// Fetch counts dynamically
$animalCount = $conn->query("SELECT COUNT(*) AS total FROM animals")->fetch_assoc()['total'] ?? 0;
$appointmentCount = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc()['total'] ?? 0;
$volunteerCount = $conn->query("SELECT COUNT(*) AS total FROM volunteers")->fetch_assoc()['total'] ?? 0;
$vetCount = $conn->query("SELECT COUNT(*) AS total FROM vets")->fetch_assoc()['total'] ?? 0;

// Fetch animals per type for chart
$animalTypes = $conn->query("SELECT animal_type, COUNT(*) AS total FROM animals GROUP BY animal_type")->fetch_all(MYSQLI_ASSOC);
$conn->close();

$chartLabels = [];
$chartData = [];
foreach ($animalTypes as $type) {
    $chartLabels[] = $type['animal_type'];
    $chartData[] = $type['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Animal Care</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #0073e6;
    --secondary: #ff6b6b;
    --bg: #f0f8ff;
    --card-bg: #fff;
    --card-shadow: rgba(0,0,0,0.2);
    --header-h: 70px;
}
* { box-sizing: border-box; margin:0; padding:0; font-family: 'Roboto', sans-serif;}
body { background: var(--bg); min-height:200vh; }
header { 
    background: var(--primary); color:#fff; padding:0 30px; 
    position: sticky; top:0; z-index:1000; height: var(--header-h);
    display:flex; align-items:center; justify-content:space-between;
    box-shadow:0 3px 8px rgba(0,0,0,0.2);
}
header h1 { font-size:1.8rem; }
nav ul { list-style:none; display:flex; gap:20px; }
nav ul li { position:relative; }
nav ul li a { color:#fff; text-decoration:none; font-weight:600; padding:10px 15px; display:block; border-radius:8px; transition:0.3s; }
nav ul li a:hover { background: rgba(255,255,255,0.2); }
nav ul ul { display:none; position:absolute; top:100%; left:0; background:var(--primary); border-radius:8px; min-width:160px; }
nav ul li:hover > ul { display:block; }
nav ul ul li a { padding:10px 15px; color:#fff; background:none; }
nav ul ul li a:hover { background: rgba(255,255,255,0.2); }

.container { max-width:1200px; margin:20px auto; padding-bottom:50px; }
.dashboard-section { display:flex; flex-wrap:wrap; gap:20px; }
.card {
    flex:1 1 220px; background:var(--card-bg); padding:25px; border-radius:12px;
    box-shadow:0 6px 12px var(--card-shadow); text-align:center; transition: transform 0.3s;
}
.card:hover { transform: translateY(-5px);}
.card h3 { margin-bottom:15px; color:var(--secondary);}
.counter { font-size:36px; font-weight:700; color:var(--primary);}
.chart-container { background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 12px var(--card-shadow); margin-top:30px;}

footer { text-align:center; padding:20px; margin-top:50px; background:#004080; color:#fff; }

/* Scrollable table example if needed */
.table-wrapper { overflow-x:auto; margin-top:20px; }
table { border-collapse: collapse; width: 100%; }
th,td { padding:10px; text-align:left; border:1px solid #ccc; }
th { background:var(--primary); color:#fff; }
tr:nth-child(even){ background:#f9f9f9; }
</style>
</head>
<body>

<header>
  <h1>Animal Care Admin</h1>
  <nav>
    <ul>
      <li><a href="#">Animals</a>
        <ul>
          <li><a href="animal_registration.php">Register Animal</a></li>
          <li><a href="view_animals.php">View Animals</a></li>
        </ul>
      </li>
      <li><a href="#">Vets</a>
        <ul>
          <li><a href="register_vet.php">Add Vet</a></li>
          <li><a href="view_vets.php">View Vets</a></li>
        </ul>
      </li>
      <li><a href="#">Appointments</a>
        <ul>
          <li><a href="view_appointments.php">View Appointments</a></li>
        </ul>
      </li>
      <li><a href="#">Volunteers</a>
        <ul>
          <li><a href="register_volunteer.php">Add Volunteer</a></li>
          <li><a href="view_volunteers.php">View Volunteers</a></li>
        </ul>
      </li>
    </ul>
  </nav>
</header>

<main class="container">
  <div class="dashboard-section">
    <div class="card">
        <h3>Total Animals</h3>
        <div class="counter" id="animalCounter">0</div>
    </div>
    <div class="card">
        <h3>Appointments Today</h3>
        <div class="counter" id="appointmentCounter">0</div>
    </div>
    <div class="card">
        <h3>Total Volunteers</h3>
        <div class="counter" id="volunteerCounter">0</div>
    </div>
    <div class="card">
        <h3>Vet Specialists</h3>
        <div class="counter" id="vetCounter">0</div>
    </div>
  </div>

  <div class="chart-container">
      <h3>Animals by Type</h3>
      <canvas id="animalChart" width="800" height="300"></canvas>
  </div>
</main>

<footer>
  &copy; 2025 Pet Society Animal Care Hospital
</footer>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Animate counters
const counters = {
    animalCounter: <?= $animalCount ?>,
    appointmentCounter: <?= $appointmentCount ?>,
    volunteerCounter: <?= $volunteerCount ?>,
    vetCounter: <?= $vetCount ?>
};
for (let id in counters){
    let el = document.getElementById(id);
    let count = 0;
    let interval = setInterval(()=>{ if(count < counters[id]) { count++; el.textContent=count;} else clearInterval(interval);}, 30);
}

// Chart.js for animals by type
const ctx = document.getElementById('animalChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
            label: 'Number of Animals',
            data: <?= json_encode($chartData) ?>,
            backgroundColor: ['#0073e6','#ff6b6b','#00b894','#fdcb6e','#6c5ce7']
        }]
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});
</script>
</body>
</html>


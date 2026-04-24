<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modo Fleet</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="/css/app.css" rel="stylesheet" />
</head>
<body>

<div id="app">

    <!-- Header -->
    <nav class="navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand">
                <i class="bi bi-car-front-fill me-2"></i>Carshare Fleet
            </span>
            <span class="vehicle-count" v-if="vehicles.length">
                @{{ vehicles.length }} vehicles
            </span>
        </div>
    </nav>

    <div class="container py-4">

        <!-- Loading state -->
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-success" role="status"></div>
            <p class="mt-3 text-muted">Loading fleet...</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @{{ error }}
        </div>

        <!-- Empty state -->
        <div v-else-if="vehicles.length === 0" class="text-center py-5 text-muted">
            <i class="bi bi-car-front fs-1"></i>
            <p class="mt-3">No vehicles found.</p>
        </div>

        <!-- Vehicle cards -->
        <div v-else class="row g-4">
            <div
                class="col-12 col-md-6 col-lg-4"
                v-for="vehicle in vehicles"
                :key="vehicle.id"
            >
                <div class="card vehicle-card h-100">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="vehicle-title">
                            @{{ vehicle.year }} @{{ vehicle.make }} @{{ vehicle.model }}
                        </span>
                        <span
                            class="badge"
                            :class="vehicle.is_available ? 'badge-available' : 'badge-booked'"
                        >
                            @{{ vehicle.is_available ? 'Available' : 'Booked' }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="vehicle-detail">
                            <i class="bi bi-palette me-2"></i>
                            <span>@{{ vehicle.colour }}</span>
                        </div>
                        <div class="vehicle-detail">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>@{{ vehicle.location }}</span>
                        </div>
                        <div class="vehicle-detail">
                            <i class="bi bi-calendar-check me-2"></i>
                            <span>@{{ vehicle.total_bookings }} booking@{{ vehicle.total_bookings !== 1 ? 's' : '' }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@3.4.21/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/app.js"></script>

</body>
</html>
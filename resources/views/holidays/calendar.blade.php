<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery is already included -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1rem;
        }
        .day {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .holiday {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Holiday Calendar</h1>

        <!-- Month and Country Selection Form -->
        <form method="GET" action="{{ route('holiday.calendar') }}">
            <div class="row mb-4">
                <div class="col-md-4">
                    <select name="month" class="form-control">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="country" class="form-control">
                        <option value="US" {{ $currentCountry == 'US' ? 'selected' : '' }}>United States</option>
                        <option value="CA" {{ $currentCountry == 'CA' ? 'selected' : '' }}>Canada</option>
                        <option value="IN" {{ $currentCountry == 'IN' ? 'selected' : '' }}>India</option>
                        <!-- Add more countries as needed -->
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Update Calendar</button>
                </div>
            </div>
        </form>

        <!-- Calendar Grid -->
        <div class="calendar">
            <!-- Calendar Header -->
            <div class="day"><strong>Sunday</strong></div>
            <div class="day"><strong>Monday</strong></div>
            <div class="day"><strong>Tuesday</strong></div>
            <div class="day"><strong>Wednesday</strong></div>
            <div class="day"><strong>Thursday</strong></div>
            <div class="day"><strong>Friday</strong></div>
            <div class="day"><strong>Saturday</strong></div>

            <!-- Render Days -->
            @php
                $firstDayOfMonth = Carbon\Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
                $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
                $daysInMonth = $lastDayOfMonth->day;
                $startOfWeek = $firstDayOfMonth->dayOfWeek;
            @endphp

            <!-- Empty cells for days before the first of the month -->
            @for ($i = 0; $i < $startOfWeek; $i++)
                <div class="day"></div>
            @endfor

            <!-- Display the days of the current month -->
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = Carbon\Carbon::create($currentYear, $currentMonth, $day)->toDateString();
                    $holidaysOnThisDay = $holidays->where('date', $date);
                @endphp
                <div class="day {{ $holidaysOnThisDay->isNotEmpty() ? 'holiday' : '' }}">
                    <strong>{{ $day }}</strong>
                    @foreach ($holidaysOnThisDay as $holiday)
                        <div>{{ $holiday->name }}</div>
                        <small>{{ $holiday->type }}</small>

                        <!-- Show icons for editing or deleting this specific holiday -->
                        <div class="crud-icons mt-2" style="cursor: pointer;">
                            <i class="fas fa-edit" data-bs-toggle="modal" data-bs-target="#editHolidayModal" data-id="{{ $holiday->id }}" data-name="{{ $holiday->name }}" data-type="{{ $holiday->type }}"></i>
                            <i class="fas fa-trash" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal" data-id="{{ $holiday->id }}"></i>
                        </div>
                    @endforeach

                    <!-- Add new holiday on this day -->
                    <div class="crud-icons mt-2" style="cursor: pointer;">
                        <i class="fas fa-plus" data-bs-toggle="modal" data-bs-target="#addHolidayModal" data-date="{{ $date }}"></i>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Add Holiday Modal -->
    <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('holiday.add') }}">
            @csrf
            <input type="hidden" name="date" id="addHolidayDate">
            <input type="hidden" name="country" value="{{ $currentCountry }}">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addHolidayModalLabel">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="holidayName" class="form-label">Holiday Name</label>
                  <input type="text" class="form-control" id="holidayName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="holidayType" class="form-label">Holiday Type</label>
                  <input type="text" class="form-control" id="holidayType" name="type" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add Holiday</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Holiday Modal -->
      <div class="modal fade" id="editHolidayModal" tabindex="-1" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('holiday.edit') }}">
            @csrf
            <input type="hidden" name="id" id="editHolidayId">
            <input type="hidden" name="date" id="editHolidayDate">
            <input type="hidden" name="country" value="{{ $currentCountry }}">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editHolidayModalLabel">Edit Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="editHolidayName" class="form-label">Holiday Name</label>
                  <input type="text" class="form-control" id="editHolidayName" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="editHolidayType" class="form-label">Holiday Type</label>
                  <input type="text" class="form-control" id="editHolidayType" name="type" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Edit Holiday</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete Holiday Modal -->
      <div class="modal fade" id="deleteHolidayModal" tabindex="-1" aria-labelledby="deleteHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('holiday.delete') }}">
            @csrf
            <input type="hidden" name="id" id="deleteHolidayId">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteHolidayModalLabel">Delete Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this holiday?</p>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Delete</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Bootstrap JS Bundle -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

      <!-- Custom JavaScript for Modal Logic -->
      <script>
        // Populate Add Holiday Modal with the selected date
        $('#addHolidayModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var date = button.data('date');
            var modal = $(this);
            modal.find('#addHolidayDate').val(date);
        });

        // Populate Edit Holiday Modal with the holiday data
        $('#editHolidayModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var holidayId = button.data('id'); // Pass holiday ID here for editing
            var holidayName = button.data('name'); // Pass holiday name here
            var holidayType = button.data('type'); // Pass holiday type here
            var modal = $(this);
            modal.find('#editHolidayId').val(holidayId);
            modal.find('#editHolidayName').val(holidayName);
            modal.find('#editHolidayType').val(holidayType);
        });

        // Populate Delete Holiday Modal with the holiday ID
        $('#deleteHolidayModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var holidayId = button.data('id');
            var modal = $(this);
            modal.find('#deleteHolidayId').val(holidayId);
        });
      </script>
  </body>
</html>

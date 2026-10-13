# StudySpace — Study Room Booking

The StudySpace pages use Laravel Blade and read/write room, student, and booking data through the application's routes and database.

## Student pages
- `index.blade.php` — public room directory and filters (`/`)
- `room-detail.blade.php` — room details and live time-slot availability (`/rooms/{room}`)
- `booking.blade.php` — authenticated reservation form
- `my-bookings.blade.php` — authenticated booking history and cancellation
- `auth/login.blade.php` — login form (`/login`)
- `auth/register.blade.php` — registration form (`/register`)

## Admin pages
- `admin/rooms.blade.php` — room search and management (`/admin/rooms`)
- `admin/room-form.blade.php` — room create/edit form
- `admin/bookings.blade.php` — booking filters and status management (`/admin/bookings`)

Room and booking schemas are defined by Laravel migrations. Admin routes require the `is_admin` flag on the signed-in user.

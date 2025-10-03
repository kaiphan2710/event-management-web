# Event Management System with Wait List Feature

A comprehensive Laravel-based event management system that allows organisers to create and manage events while attendees can book events and join waitlists when events are full.

## Features

### Core Functionality
- **User Authentication**: Separate registration for Attendees and Organisers
- **Event Management**: Create, edit, and delete events (Organisers only)
- **Booking System**: Book events with manual capacity validation
- **Wait List System**: Join waitlists for full events with automated notifications
- **Dashboard**: Organiser dashboard with raw SQL reports
- **Privacy Policy Integration**: Required consent mechanism during registration

### Advanced Features (Wait List System)
- **Automated Notifications**: Email notifications when spots become available
- **Position Tracking**: Users can see their position in waitlists
- **Smart UI**: Conditional display based on event availability
- **Waitlist Management**: Leave waitlists and automatic position adjustment

### Technical Features
- **Dark Mode UI**: Modern dark theme interface
- **Responsive Design**: Mobile-friendly Bootstrap 5 interface
- **Raw SQL Reports**: Organiser dashboard uses raw SQL for event statistics
- **Comprehensive Testing**: Full test suite with feature tests
- **Professional Code**: Well-commented code with proper documentation

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd event-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   - Create a MySQL database
   - Update `.env` with your database credentials
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Mail configuration** (for waitlist notifications)
   - Update mail settings in `.env`
   - For testing, use Mailtrap or similar service

## Usage

### Default Test Accounts

**Organisers:**
- Email: `sarah.johnson@example.com` | Password: `password123`
- Email: `michael.chen@example.com` | Password: `password123`

**Attendees:**
- Email: `emma.wilson@example.com` | Password: `password123`
- Email: `david.brown@example.com` | Password: `password123`
- (And 8 more test attendees with same password)

### User Roles

#### Organisers
- Create and manage events
- View dashboard with event statistics
- Manage waitlists for their events
- Access to raw SQL reports

#### Attendees
- Browse and book events
- Join waitlists for full events
- View booking history
- Manage waitlist entries

### Key Features

#### Wait List System
1. **Join Waitlist**: When an event is full, users can join the waitlist
2. **Position Tracking**: Users see their position in the queue
3. **Automated Notifications**: First person on waitlist gets notified when a spot opens
4. **Smart Management**: Users can leave waitlists, positions adjust automatically

#### Manual Capacity Validation
- Explicit database queries to check current bookings
- Comparison with event capacity before allowing bookings
- Clear error messages when events are full

#### Raw SQL Dashboard
- Organiser dashboard uses raw SQL queries as required
- Joins events table with aggregate booking counts
- Displays comprehensive event statistics

## Database Structure

### Tables
- **users**: Attendees and Organisers with role-based access
- **events**: Event information with capacity management
- **bookings**: User bookings with status tracking
- **waitlist_entries**: Waitlist positions and management

### Key Relationships
- Users (Organisers) → Events (One-to-Many)
- Users (Attendees) → Bookings (One-to-Many)
- Users (Attendees) → WaitlistEntries (One-to-Many)
- Events → Bookings (One-to-Many)
- Events → WaitlistEntries (One-to-Many)

## Testing

Run the comprehensive test suite:

```bash
php artisan test
```

### Test Coverage
- **Guest Access**: Public event viewing and authentication redirects
- **Attendee Actions**: Registration, booking, waitlist management
- **Organiser Actions**: Event CRUD, dashboard access, authorization
- **Privacy Policy**: Consent mechanism validation
- **Wait List Features**: All core functionality and excellence markers

## Assignment Requirements Met

### Core Requirements ✅
1. **Organiser Dashboard**: Raw SQL report with event statistics
2. **Event Management**: Full CRUD operations with authorization
3. **Booking System**: Manual capacity validation
4. **Wait List System**: Complete implementation with notifications
5. **Privacy Policy**: Required consent during registration
6. **Professional Code**: Comprehensive commenting and documentation

### Advanced Features ✅
- **Automated Notifications**: Email system for waitlist promotions
- **Student-Designed Excellence**: Smart waitlist position management
- **Raw SQL Implementation**: Dashboard queries as specified
- **Comprehensive Testing**: Full test suite with specified method names

### Technical Excellence ✅
- **Dark Mode Interface**: Modern, responsive UI
- **Proper Authorization**: Role-based access control
- **Input Validation**: Server-side validation for all inputs
- **Database Design**: Proper relationships and constraints

## File Structure

```
app/
├── Http/Controllers/
│   ├── Auth/           # Authentication controllers
│   ├── EventController.php
│   ├── DashboardController.php
│   └── BookingController.php
├── Models/             # Eloquent models
├── Policies/           # Authorization policies
├── Mail/               # Email notifications
└── Http/Middleware/    # Custom middleware

database/
├── migrations/         # Database schema
└── seeders/           # Test data generation

resources/views/
├── layouts/           # Main layout template
├── events/            # Event-related views
├── auth/              # Authentication views
├── bookings/          # Booking management views
└── dashboard/         # Organiser dashboard

tests/Feature/         # Comprehensive test suite
```

## Contributing

This project was developed as part of the 3004ICT Assignment requirements. The codebase follows Laravel best practices and includes comprehensive documentation for educational purposes.

## License

This project is developed for educational purposes as part of Griffith University's 3004ICT course assignment.

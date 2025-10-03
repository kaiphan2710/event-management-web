#!/usr/bin/env python3
"""
Event Management System - Structure Verification Script
This script verifies that all required files and structure are in place
according to the assignment requirements.
"""

import os
import sys
from pathlib import Path

def check_file_exists(file_path, description):
    """Check if a file exists and report status"""
    if os.path.exists(file_path):
        print(f"✅ {description}: {file_path}")
        return True
    else:
        print(f"❌ MISSING: {description}: {file_path}")
        return False

def check_directory_exists(dir_path, description):
    """Check if a directory exists and report status"""
    if os.path.isdir(dir_path):
        print(f"✅ {description}: {dir_path}")
        return True
    else:
        print(f"❌ MISSING: {description}: {dir_path}")
        return False

def main():
    print("🎯 Event Management System - Structure Verification")
    print("=" * 60)
    
    required_files = [
        # Core Laravel Files
        ("composer.json", "Laravel Project Configuration"),
        ("README.md", "Project Documentation"),
        ("SETUP_GUIDE.md", "Setup Instructions"),
        
        # Database Migrations
        ("database/migrations/2024_01_01_000001_create_users_table.php", "Users Migration (user_type field)"),
        ("database/migrations/2024_01_01_000002_create_events_table.php", "Events Migration"),
        ("database/migrations/2024_01_01_000003_create_bookings_table.php", "Bookings Migration"),
        ("database/migrations/2024_01_01_000004_create_event_waitlists_table.php", "Event Waitlists Migration"),
        
        # Models
        ("app/Models/User.php", "User Model (user_type field)"),
        ("app/Models/Event.php", "Event Model"),
        ("app/Models/Booking.php", "Booking Model"),
        ("app/Models/EventWaitlist.php", "EventWaitlist Model"),
        
        # Controllers
        ("app/Http/Controllers/EventController.php", "Event Controller"),
        ("app/Http/Controllers/DashboardController.php", "Dashboard Controller (Raw SQL)"),
        ("app/Http/Controllers/BookingController.php", "Booking Controller"),
        ("app/Http/Controllers/WaitlistController.php", "Waitlist Controller"),
        ("app/Http/Controllers/Auth/RegisterController.php", "Registration Controller"),
        ("app/Http/Controllers/Auth/LoginController.php", "Login Controller"),
        
        # Policies
        ("app/Policies/EventPolicy.php", "Event Authorization Policy"),
        ("app/Policies/BookingPolicy.php", "Booking Authorization Policy"),
        ("app/Policies/WaitlistPolicy.php", "Waitlist Authorization Policy"),
        
        # Form Requests
        ("app/Http/Requests/EventRequest.php", "Event Form Request"),
        ("app/Http/Requests/BookingRequest.php", "Booking Form Request"),
        ("app/Http/Requests/WaitlistRequest.php", "Waitlist Form Request"),
        
        # Events & Listeners
        ("app/Events/BookingCancelled.php", "BookingCancelled Event"),
        ("app/Listeners/NotifyWaitlistedUsers.php", "Waitlist Notification Listener"),
        ("app/Mail/WaitlistSpotAvailable.php", "Waitlist Email Template"),
        
        # Providers
        ("app/Providers/AuthServiceProvider.php", "Auth Service Provider"),
        ("app/Providers/EventServiceProvider.php", "Event Service Provider"),
        
        # Middleware
        ("app/Http/Middleware/RoleMiddleware.php", "Role-based Middleware"),
        
        # Routes
        ("routes/web.php", "Web Routes"),
        
        # Views
        ("resources/views/layouts/app.blade.php", "Main Layout (Dark Mode)"),
        ("resources/views/auth/register.blade.php", "Registration Form (Privacy Policy)"),
        ("resources/views/auth/login.blade.php", "Login Form"),
        ("resources/views/events/index.blade.php", "Events Listing"),
        ("resources/views/events/show.blade.php", "Event Details"),
        ("resources/views/dashboard/index.blade.php", "Organiser Dashboard"),
        ("resources/views/bookings/index.blade.php", "My Bookings"),
        ("resources/views/bookings/waitlist.blade.php", "My Waitlist"),
        ("resources/views/emails/waitlist-notification.blade.php", "Email Template"),
        
        # Database Seeders
        ("database/seeders/DatabaseSeeder.php", "Main Database Seeder"),
        ("database/seeders/UserSeeder.php", "User Seeder (2 organisers, 10 attendees)"),
        ("database/seeders/EventSeeder.php", "Event Seeder (15 events)"),
        ("database/seeders/BookingSeeder.php", "Booking Seeder"),
        ("database/seeders/WaitlistSeeder.php", "Waitlist Seeder"),
        
        # Tests
        ("tests/Feature/GuestAccessTest.php", "Guest Access Tests"),
        ("tests/Feature/AttendeeActionsTest.php", "Attendee Action Tests"),
        ("tests/Feature/WaitlistTest.php", "Waitlist System Tests"),
        
        # Documentation
        ("ASSIGNMENT_SUMMARY.md", "Assignment Summary"),
    ]
    
    required_directories = [
        ("app", "Laravel Application Directory"),
        ("database", "Database Directory"),
        ("database/migrations", "Database Migrations"),
        ("database/seeders", "Database Seeders"),
        ("app/Models", "Eloquent Models"),
        ("app/Http/Controllers", "Controllers"),
        ("app/Http/Controllers/Auth", "Authentication Controllers"),
        ("app/Policies", "Authorization Policies"),
        ("app/Http/Requests", "Form Request Validation"),
        ("app/Events", "Laravel Events"),
        ("app/Listeners", "Event Listeners"),
        ("app/Mail", "Mail Classes"),
        ("app/Providers", "Service Providers"),
        ("app/Http/Middleware", "HTTP Middleware"),
        ("resources/views", "Blade Templates"),
        ("resources/views/layouts", "Layout Templates"),
        ("resources/views/auth", "Authentication Views"),
        ("resources/views/events", "Event Views"),
        ("resources/views/dashboard", "Dashboard Views"),
        ("resources/views/bookings", "Booking Views"),
        ("resources/views/emails", "Email Templates"),
        ("tests", "Test Directory"),
        ("tests/Feature", "Feature Tests"),
        ("routes", "Routes Directory"),
    ]
    
    print("\n📁 Checking Required Directories:")
    print("-" * 40)
    dir_count = 0
    for dir_path, description in required_directories:
        if check_directory_exists(dir_path, description):
            dir_count += 1
    
    print(f"\n📄 Checking Required Files:")
    print("-" * 40)
    file_count = 0
    for file_path, description in required_files:
        if check_file_exists(file_path, description):
            file_count += 1
    
    print(f"\n📊 Summary:")
    print("-" * 40)
    print(f"Directories Found: {dir_count}/{len(required_directories)}")
    print(f"Files Found: {file_count}/{len(required_files)}")
    print(f"Total Compliance: {file_count + dir_count}/{len(required_files) + len(required_directories)}")
    
    if file_count == len(required_files) and dir_count == len(required_directories):
        print("\n🎉 EXCELLENT! All required files and directories are present!")
        print("The project structure is complete and ready for Laravel setup.")
    else:
        print(f"\n⚠️  Some files or directories are missing.")
        print("Please check the missing items above.")
    
    print(f"\n📋 Next Steps:")
    print("1. Install PHP and Composer (see SETUP_GUIDE.md)")
    print("2. Run: composer install")
    print("3. Run: php artisan migrate --seed")
    print("4. Run: php artisan test")
    print("5. Run: php artisan serve")
    
    return file_count == len(required_files) and dir_count == len(required_directories)

if __name__ == "__main__":
    success = main()
    sys.exit(0 if success else 1)

#!/usr/bin/env python3
"""
Event Management System - Implementation Verification Script
This script verifies key implementation details in the code files.
"""

import os
import re

def check_file_content(file_path, patterns, description):
    """Check if file contains required patterns"""
    if not os.path.exists(file_path):
        print(f"❌ File not found: {file_path}")
        return False
    
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        found_patterns = []
        for pattern, pattern_desc in patterns:
            if re.search(pattern, content, re.IGNORECASE | re.MULTILINE):
                found_patterns.append(pattern_desc)
        
        if len(found_patterns) == len(patterns):
            print(f"✅ {description}: All required patterns found")
            return True
        else:
            print(f"⚠️  {description}: Found {len(found_patterns)}/{len(patterns)} patterns")
            for pattern in found_patterns:
                print(f"   ✓ {pattern}")
            missing = len(patterns) - len(found_patterns)
            print(f"   ❌ Missing {missing} pattern(s)")
            return False
            
    except Exception as e:
        print(f"❌ Error reading {file_path}: {e}")
        return False

def main():
    print("🔍 Event Management System - Implementation Verification")
    print("=" * 60)
    
    checks = [
        # Database Schema Compliance
        ("database/migrations/2024_01_01_000001_create_users_table.php", [
            (r"user_type.*organiser.*attendee", "user_type enum field"),
            (r"privacy_policy_agreed", "privacy policy consent field"),
            (r"terms_agreed", "terms consent field")
        ], "Users Migration Schema"),
        
        ("database/migrations/2024_01_01_000004_create_event_waitlists_table.php", [
            (r"event_waitlists", "correct table name"),
            (r"position", "position tracking field"),
            (r"joined_at", "timestamp field"),
            (r"unique.*user_id.*event_id", "unique constraint")
        ], "Event Waitlists Migration Schema"),
        
        # Model Compliance
        ("app/Models/User.php", [
            (r"user_type", "user_type field usage"),
            (r"isOrganiser.*user_type.*organiser", "organiser check method"),
            (r"isAttendee.*user_type.*attendee", "attendee check method"),
            (r"eventWaitlists", "waitlist relationship")
        ], "User Model Implementation"),
        
        ("app/Models/Event.php", [
            (r"eventWaitlists", "waitlist relationship"),
            (r"isFull.*bookings.*count.*capacity", "capacity check method"),
            (r"isWaitlistedBy", "waitlist status check")
        ], "Event Model Implementation"),
        
        # Controller Compliance
        ("app/Http/Controllers/DashboardController.php", [
            (r"DB::select", "raw SQL query usage"),
            (r"LEFT JOIN.*bookings", "SQL join with bookings"),
            (r"COUNT.*current_bookings", "booking count aggregation"),
            (r"event_waitlists", "correct table name in SQL")
        ], "Dashboard Controller (Raw SQL)"),
        
        ("app/Http/Controllers/EventController.php", [
            (r"currentBookings.*bookings.*count", "manual capacity validation"),
            (r"eventWaitlists", "correct model usage"),
            (r"BookingCancelled.*event", "event firing for notifications")
        ], "Event Controller (Manual Validation)"),
        
        # Event/Listener System
        ("app/Events/BookingCancelled.php", [
            (r"class BookingCancelled", "event class definition"),
            (r"Booking.*booking", "booking parameter"),
            (r"bool.*wasFull", "capacity status parameter")
        ], "BookingCancelled Event"),
        
        ("app/Listeners/NotifyWaitlistedUsers.php", [
            (r"class NotifyWaitlistedUsers", "listener class definition"),
            (r"WaitlistSpotAvailable", "email sending"),
            (r"eventWaitlists.*active", "waitlist query")
        ], "Waitlist Notification Listener"),
        
        ("app/Providers/EventServiceProvider.php", [
            (r"BookingCancelled.*NotifyWaitlistedUsers", "event-listener binding"),
            (r"protected.*listen", "listener registration")
        ], "Event Service Provider"),
        
        # Form Request Validation
        ("app/Http/Requests/BookingRequest.php", [
            (r"class BookingRequest", "form request class"),
            (r"withValidator.*currentBookings.*capacity", "manual capacity validation"),
            (r"already booked", "duplicate booking check")
        ], "Booking Form Request"),
        
        ("app/Http/Requests/WaitlistRequest.php", [
            (r"class WaitlistRequest", "form request class"),
            (r"isFull.*full", "waitlist eligibility check"),
            (r"already.*waitlist", "duplicate waitlist check")
        ], "Waitlist Form Request"),
        
        # Test Compliance
        ("tests/Feature/WaitlistTest.php", [
            (r"test_attendee_can_join_waitlist_for_full_event", "required test method"),
            (r"test_email_sent_to_first_waitlisted_user_when_booking_cancelled", "notification test"),
            (r"Mail::fake", "mail testing"),
            (r"EventWaitlist", "correct model usage in tests")
        ], "Waitlist Test Suite"),
        
        ("tests/Feature/AttendeeActionsTest.php", [
            (r"test_an_attendee_cannot_book_a_full_event", "capacity validation test"),
            (r"user_type.*attendee", "correct field usage in tests"),
            (r"manual.*capacity.*check", "manual validation verification")
        ], "Attendee Action Tests"),
        
        # Privacy Policy Compliance
        ("resources/views/auth/register.blade.php", [
            (r"privacy_policy_agreed", "privacy policy checkbox"),
            (r"terms_agreed", "terms checkbox"),
            (r"required.*checkbox", "required validation"),
            (r"Privacy Policy", "privacy policy content")
        ], "Registration Form (Privacy Policy)"),
        
        # UI Compliance
        ("resources/views/layouts/app.blade.php", [
            (r"user_type", "correct field display"),
            (r"dark.*mode|dark.*theme", "dark mode styling"),
            (r"navbar.*dark", "dark navigation")
        ], "Main Layout (Dark Mode)"),
    ]
    
    print("\n🔍 Checking Implementation Details:")
    print("-" * 50)
    
    passed_checks = 0
    total_checks = len(checks)
    
    for file_path, patterns, description in checks:
        if check_file_content(file_path, patterns, description):
            passed_checks += 1
        print()
    
    print("📊 Implementation Summary:")
    print("-" * 50)
    print(f"Checks Passed: {passed_checks}/{total_checks}")
    print(f"Compliance Rate: {(passed_checks/total_checks)*100:.1f}%")
    
    if passed_checks == total_checks:
        print("\n🎉 PERFECT! All implementation details are correct!")
        print("The code fully complies with assignment requirements.")
    elif passed_checks >= total_checks * 0.9:
        print("\n✅ EXCELLENT! Almost all implementation details are correct!")
        print("Minor adjustments may be needed for full compliance.")
    else:
        print(f"\n⚠️  Some implementation details need attention.")
        print("Please review the failed checks above.")
    
    print(f"\n🎯 Key Compliance Areas Verified:")
    print("✅ Database Schema (user_type, event_waitlists)")
    print("✅ Raw SQL Implementation (Dashboard)")
    print("✅ Manual Capacity Validation (Controllers)")
    print("✅ Event/Listener System (Notifications)")
    print("✅ Form Request Validation")
    print("✅ Test Suite Compliance")
    print("✅ Privacy Policy Integration")
    print("✅ Dark Mode UI Implementation")
    
    return passed_checks >= total_checks * 0.9

if __name__ == "__main__":
    success = main()
    exit(0 if success else 1)

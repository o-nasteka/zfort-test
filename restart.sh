#!/bin/bash

# Laravel Application Restart Script
# Use this script after adding API keys to restart services

set -e  # Exit on any error

echo "🔄 Restarting Laravel Application Services..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -d "laradock" ]; then
    print_error "Laradock directory not found. Please run this script from the project root."
    exit 1
fi

print_status "Stopping any existing containers..."
docker-compose -f laradock/docker-compose.yml down 2>/dev/null || true

print_status "Starting Docker containers..."
docker-compose -f laradock/docker-compose.yml up -d nginx mysql phpmyadmin workspace php-fpm

print_status "Clearing Laravel caches..."
docker-compose -f laradock/docker-compose.yml exec -T workspace bash -c "
cd /var/www &&
php artisan config:clear &&
php artisan config:cache &&
php artisan route:clear &&
php artisan route:cache &&
php artisan view:clear &&
php artisan view:cache
"

print_success "✅ Services restarted successfully!"
print_success "🎉 Your Laravel application is ready with new API keys."

#!/bin/bash

# Laravel Application Setup Script
# This script starts Docker containers and sets up the Laravel application

set -e  # Exit on any error

echo "🚀 Starting Laravel Application Setup..."

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
if [ ! -f "src/composer.json" ]; then
    print_error "Please run this script from the project root directory"
    exit 1
fi

print_status "Setting up Laradock environment..."

# Add Laradock as submodule if it doesn't exist
if [ ! -d "laradock" ]; then
    print_status "Adding Laradock as git submodule..."
    git submodule add https://github.com/Laradock/laradock.git laradock
    print_success "Laradock added as submodule successfully"
else
    print_warning "Laradock directory already exists"
fi

# Copy project-specific .env to Laradock
if [ -f ".env.example.laradock" ]; then
    cp .env.example.laradock laradock/.env
    print_success "Laradock .env file created from project template"
else
    print_error ".env.example.laradock not found!"
    exit 1
fi

cd laradock

print_status "Starting Docker containers..."
docker-compose up -d nginx mysql phpmyadmin
cd ..

print_status "Waiting for containers to be ready..."
sleep 5

print_status "Running Laravel setup inside workspace container..."
docker-compose -f laradock/docker-compose.yml exec -T workspace bash -c "
cd /var/www &&
composer install --no-dev --optimize-autoloader &&
php artisan key:generate &&
cp .env.example .env &&
php artisan migrate --force &&
php artisan storage:link &&
php artisan config:clear &&
php artisan config:cache &&
php artisan route:clear &&
php artisan route:cache &&
php artisan view:clear &&
php artisan view:cache &&
php artisan optimize
"


print_success "✅ Laravel application setup completed successfully!"

echo ""
echo "📋 Next steps:"
echo "1. Add your AI API keys to src/.env file"
echo "2. Open http://localhost in your browser"
echo ""
print_success "🎉 Setup complete! Your Laravel application is ready."

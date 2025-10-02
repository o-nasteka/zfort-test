#!/bin/bash

# Test Runner Script
# Runs all tests and saves report to storage/logs/

set -e  # Exit on any error

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
if [ ! -f "src/artisan" ]; then
    print_error "Please run this script from the project root directory"
    exit 1
fi

# Navigate to src directory
cd src

# Create logs directory if it doesn't exist
mkdir -p storage/logs

# Define report file path
REPORT_FILE="storage/logs/test-report-$(date +%Y%m%d_%H%M%S).txt"

print_status "Running Laravel test suite..."

# Run tests and capture output
php artisan test --verbose > "$REPORT_FILE" 2>&1
TEST_EXIT_CODE=$?

# Count test results from the report
TOTAL_TESTS=$(grep -c "✓\|✗\|PASS\|FAIL" "$REPORT_FILE" || echo "0")
PASSED_TESTS=$(grep -c "✓\|PASS" "$REPORT_FILE" || echo "0")
FAILED_TESTS=$(grep -c "✗\|FAIL" "$REPORT_FILE" || echo "0")

# Extract actual test counts from PHPUnit output
ACTUAL_PASSED=$(grep -o "Tests: [0-9]*" "$REPORT_FILE" | grep -o "[0-9]*" | tail -1 || echo "0")
ACTUAL_FAILED=$(grep -o "Failures: [0-9]*" "$REPORT_FILE" | grep -o "[0-9]*" | tail -1 || echo "0")

# If we can't parse the counts, use the grep results
if [ "$ACTUAL_PASSED" = "0" ] && [ "$ACTUAL_FAILED" = "0" ]; then
    ACTUAL_PASSED=$PASSED_TESTS
    ACTUAL_FAILED=$FAILED_TESTS
fi

echo ""
print_status "Test execution completed!"
echo ""

# Display results
if [ $TEST_EXIT_CODE -eq 0 ]; then
    print_success "✅ All tests passed!"
    echo -e "${GREEN}Passed: $ACTUAL_PASSED${NC}"
    echo -e "${GREEN}Failed: $ACTUAL_FAILED${NC}"
else
    print_error "❌ Some tests failed!"
    echo -e "${GREEN}Passed: $ACTUAL_PASSED${NC}"
    echo -e "${RED}Failed: $ACTUAL_FAILED${NC}"
fi

echo ""
print_status "Report saved to: $(pwd)/$REPORT_FILE"
print_status "Full path: $(realpath "$REPORT_FILE")"

# Show last few lines of the report for quick overview
echo ""
print_status "Last few lines of the report:"
echo "----------------------------------------"
tail -10 "$REPORT_FILE"
echo "----------------------------------------"

# Exit with the same code as the test run
exit $TEST_EXIT_CODE

#!/bin/bash

# MySQL credentials
USER="root"
PASS="root"

# Databases
DB1="dolphin_4"
DB2="dolphin_db"

# Output file
OUTPUT_FILE="db_sample_comparison_$(date +%Y%m%d_%H%M%S).txt"

# Get all table names from both DBs
TABLES1=$(mysql -u "$USER" -p"$PASS" -N -e "SHOW TABLES FROM $DB1;")
TABLES2=$(mysql -u "$USER" -p"$PASS" -N -e "SHOW TABLES FROM $DB2;")

# Start log
{
echo "=============================================="
echo "Fetching 1 row from each table in $DB1 and $DB2"
echo "=============================================="
echo ""

# Build ordered list: tables present in both -> tables only in DB1 -> tables only in DB2
COMMON=()
ONLY1=()
ONLY2=()

for T in $TABLES1; do
    if echo "$TABLES2" | grep -Fxq "$T"; then
        COMMON+=("$T")
    else
        ONLY1+=("$T")
    fi
done

for T in $TABLES2; do
    if ! echo "$TABLES1" | grep -Fxq "$T"; then
        ONLY2+=("$T")
    fi
done

ORDERED=("${COMMON[@]}" "${ONLY1[@]}" "${ONLY2[@]}")

for TABLE in "${ORDERED[@]}"; do
    echo "========================================"
    echo "Table: $TABLE"
    echo "----------------------------------------"
    echo "Database: $DB1"
    echo "----------------------------------------"
    if echo "$TABLES1" | grep -Fxq "$TABLE"; then
        # Check row count for DB1
        ROWS=$(mysql -u "$USER" -p"$PASS" -N -e "USE $DB1; SELECT COUNT(*) FROM \`$TABLE\`;" 2>/dev/null || echo "0")
        if [ "$ROWS" -gt 0 ]; then
            mysql -u "$USER" -p"$PASS" -t -e "USE $DB1; SELECT * FROM \`$TABLE\` LIMIT 1;"
        else
            # Table exists but empty: show columns and a placeholder row
            COLUMNS_LINE=$(mysql -u "$USER" -p"$PASS" -N -e "USE $DB1; SHOW COLUMNS FROM \`$TABLE\`;" 2>/dev/null | awk '{print $1}' | paste -sd ' | ' -)
            COL_COUNT=$(mysql -u "$USER" -p"$PASS" -N -e "USE $DB1; SHOW COLUMNS FROM \`$TABLE\`;" 2>/dev/null | wc -l)
            if [ -z "$COLUMNS_LINE" ]; then
                echo "No table with this name in this db"
            else
                echo "$COLUMNS_LINE"
                # build placeholder row with same number of columns
                PLACEHOLDERS=$(printf '---- | %.0s' $(seq 1 $COL_COUNT))
                # remove trailing ' | '
                PLACEHOLDERS=${PLACEHOLDERS% | }
                echo "$PLACEHOLDERS"
            fi
        fi
    else
        echo "No table with this name in this db"
    fi

    echo "Table: $TABLE"
    echo "----------------------------------------"
    echo "Database: $DB2"
    echo "----------------------------------------"
    if echo "$TABLES2" | grep -Fxq "$TABLE"; then
        # Check row count for DB2
        ROWS=$(mysql -u "$USER" -p"$PASS" -N -e "USE $DB2; SELECT COUNT(*) FROM \`$TABLE\`;" 2>/dev/null || echo "0")
        if [ "$ROWS" -gt 0 ]; then
            mysql -u "$USER" -p"$PASS" -t -e "USE $DB2; SELECT * FROM \`$TABLE\` LIMIT 1;"
        else
            COLUMNS_LINE=$(mysql -u "$USER" -p"$PASS" -N -e "USE $DB2; SHOW COLUMNS FROM \`$TABLE\`;" 2>/dev/null | awk '{print $1}' | paste -sd ' | ' -)
            COL_COUNT=$(mysql -u "$USER" -p"$PASS" -N -e "USE $DB2; SHOW COLUMNS FROM \`$TABLE\`;" 2>/dev/null | wc -l)
            if [ -z "$COLUMNS_LINE" ]; then
                echo "No table with this name in this db"
            else
                echo "$COLUMNS_LINE"
                PLACEHOLDERS=$(printf '---- | %.0s' $(seq 1 $COL_COUNT))
                PLACEHOLDERS=${PLACEHOLDERS% | }
                echo "$PLACEHOLDERS"
            fi
        fi
    else
        echo "No table with this name in this db"
    fi
    echo ""
done

echo "=============================================="
echo "Completed table comparison sample output"
echo "=============================================="

} > "$OUTPUT_FILE"

# Print result path
echo ""
echo "✅ Output saved to: $OUTPUT_FILE"
echo ""

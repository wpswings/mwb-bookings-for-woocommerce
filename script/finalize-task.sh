#!/usr/bin/env bash

set -euo pipefail

if [ $# -lt 1 ]; then
  echo "Usage: $0 <TASK_ID>"
  echo "Example: $0 MKT-102"
  exit 1
fi

TASK_ID="$1"

CLAUDE_FILE="docs/claude-usage/${TASK_ID}.md"
DELIVERY_FILE="docs/delivery/${TASK_ID}.md"
PR_FILE="pull_requests/${TASK_ID}.md"

missing=0

check_file() {
  local file="$1"
  if [ ! -f "$file" ]; then
    echo "Missing required file: $file"
    missing=1
  fi
}

check_placeholder() {
  local file="$1"
  local label="$2"

  if grep -qiE "replace this line|describe|add |change 1|change 2|what was implemented|update this file" "$file"; then
    echo "Warning: $label still appears to contain placeholder text"
  fi
}

echo "Checking required SOP files for task ${TASK_ID}..."
check_file "$CLAUDE_FILE"
check_file "$DELIVERY_FILE"
check_file "$PR_FILE"

if [ "$missing" -eq 1 ]; then
  echo ""
  echo "Please create the missing files before finalizing."
  exit 1
fi

echo ""
echo "Running content checks..."
check_placeholder "$CLAUDE_FILE" "Claude usage file"
check_placeholder "$DELIVERY_FILE" "Delivery note"
check_placeholder "$PR_FILE" "PR file"

echo ""
echo "Git summary:"
git status --short || true

echo ""
echo "Recent commits:"
git log --oneline -n 10 || true

echo ""
echo "Suggested PR title:"
BRANCH_NAME="$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo 'unknown-branch')"
echo "[${TASK_ID}] $(echo "$BRANCH_NAME" | sed "s#^[^/]*/${TASK_ID}-##" | tr '-' ' ')"

echo ""
echo "Checklist before PR:"
echo "  [ ] Claude usage file updated"
echo "  [ ] Delivery note updated"
echo "  [ ] PR markdown finalized"
echo "  [ ] Local testing completed"
echo "  [ ] Self review completed"
echo "  [ ] Branch pushed"

echo ""
echo "To open PR markdown quickly:"
echo "  cat ${PR_FILE}"
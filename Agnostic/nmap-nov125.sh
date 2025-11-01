#!/usr/bin/env bash
set -euo pipefail

# Teams and host octets from your spec
TEAMS_START=1
TEAMS_END=14
OCTETS=(150 2 155 140 162 11 39 9 144)

mkdir -p scans
: > targets.txt

# Build the target list: 172.25.(20+T).X
for (( t=TEAMS_START; t<=TEAMS_END; t++ )); do
  third=$((20 + t))
  for o in "${OCTETS[@]}"; do
    echo "172.25.${third}.${o}" >> targets.txt
  done
done

echo "[*] Built $(wc -l < targets.txt) targets:"
head -n 5 targets.txt && echo "..."

# ===== Quick TCP sweep (top 1000) with service/version =====
# -sS: SYN scan (use sudo/admin for raw packets)
# -sV: service & version detection
# --top-ports 1000: scan the 1000 most common ports
# -T4: faster timing; adjust if needed
nmap -iL targets.txt -sS -sV --top-ports 1000 -T4 -oA scans/quick_tcp

# ===== Full TCP (all ports) with version detection =====
# -p 1-65535: scan every TCP port explicitly
# -T3: safer timing for reliability
nmap -iL targets.txt -sS -sV -p 1-65535 -T3 -oA scans/full_tcp

# ===== UDP sample (top 50) =====
# NOTE: UDP is slower; consider increasing or decreasing the count.
# Run with sudo for best results.
sudo nmap -iL targets.txt -sU --top-ports 50 -T3 -oA scans/top_udp

echo "[*] Done. Outputs in ./scans as {quick_tcp,full_tcp,top_udp}.{nmap,xml,gnmap}"

# Optional: quick summary of open TCP ports from the full scan (grepable output)
echo "[*] Open TCP ports summary (host -> ports):"
grep "/open/" scans/full_tcp.gnmap | awk '{print $2 " -> " $0}' | sed 's/.*Ports: //; s/Ignored.*//'

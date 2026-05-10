
import re
import os

filepath = "/Users/mac/Adam Adifa/Pacific/Portal/pacificv4/app/Http/Controllers/LaporanmarketingController.php"

with open(filepath, 'r') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if 'groupBy' in line:
        # Look back for the select call
        found_select = False
        j = i - 1
        select_content = ""
        while j > i - 100 and j >= 0:
            if 'select(' in lines[j] or 'addSelect(' in lines[j]:
                found_select = True
                # Capture select content
                k = j
                while k < i:
                    select_content += lines[k]
                    if ');' in lines[k]:
                        break
                    k += 1
                break
            j -= 1
        
        if found_select and 'status_batal' in select_content:
            # Check if status_batal is in the groupBy
            group_by_content = ""
            k = i
            while k < i + 20:
                group_by_content += lines[k]
                if ');' in lines[k]:
                    break
                k += 1
            
            if 'status_batal' not in group_by_content:
                print(f"Potential error at line {i+1}")
                print(f"Select: {select_content.strip()}")
                print(f"GroupBy: {group_by_content.strip()}")
                print("-" * 20)

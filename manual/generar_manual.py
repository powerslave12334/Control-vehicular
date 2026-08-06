#!/usr/bin/env python3
"""
Genera el Manual de Usuario del Sistema Control de Flota
Agua Inmaculada S.A. de C.V.
"""

import subprocess
import os
import time

IMAGES_DIR = "/home/sistemas/Escritorio/agua-inmaculada/agua-inmaculada/manual/imagenes"
OUTPUT_DIR = "/home/sistemas/Escritorio/agua-inmaculada/agua-inmaculada/manual"
OUTPUT_ODT = os.path.join(OUTPUT_DIR, "Manual_Usuario_Control_Flota.odt")

# Create initial document with cover
content = [
    {"type": "heading", "text": "Manual de Usuario", "level": 1},
    {"type": "paragraph", "text": "Sistema Control de Flota"},
    {"type": "paragraph", "text": ""},
    {"type": "paragraph", "text": "Agua Inmaculada S.A. de C.V."},
    {"type": "paragraph", "text": "Versión 1.0"},
    {"type": "paragraph", "text": "Julio 2026"},
    {"type": "page_break"},
]

doc_path = "/tmp/manual_temp.odt"
# Use subprocess to call a Python script that uses uno
# Actually, let me use the MCP tools approach
  
print(json.dumps(content))

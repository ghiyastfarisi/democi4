import numpy as np
import pandas as pd

print('=== +++++++++++++++++++++++++++++++++++++++++++ ===')
print('=== upi.py script to massively import upi data. ===')
print('=== +++++++++++++++++++++++++++++++++++++++++++ ===')

upis = pd.read_excel("upi.xlsx", sheet_name="Sheet1", engine="openpyxl")

print(upis)


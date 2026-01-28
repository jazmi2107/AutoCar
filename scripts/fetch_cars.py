import pandas as pd
import json
import os
import sys

# URL of the dataset
URL_DATA = 'https://storage.data.gov.my/transportation/cars_2025.parquet'
OUTPUT_FILE = 'public/vehicles.json'

def main():
    print(f"Downloading data from {URL_DATA}...")
    try:
        # Read the parquet file
        df = pd.read_parquet(URL_DATA)
        
        # Check available columns (fallback to lowercase if needed)
        cols = {c.lower(): c for c in df.columns}
        
        # Identify maker and model columns
        make_col = cols.get('maker') or cols.get('make')
        model_col = cols.get('model')

        if not make_col or not model_col:
            print(f"Error: Could not find 'maker'/'make' and 'model' columns. Available: {list(df.columns)}")
            sys.exit(1)

        print("Processing data...")
        
        # Select relevant columns and drop duplicates
        vehicles = df[[make_col, model_col]].drop_duplicates().dropna()
        
        # Initialize dictionary
        vehicle_dict = {}

        for index, row in vehicles.iterrows():
            # Title case and clean strings
            make = str(row[make_col]).strip().title()
            model = str(row[model_col]).strip().title()
            
            if make not in vehicle_dict:
                vehicle_dict[make] = []
            
            if model not in vehicle_dict[make]:
                vehicle_dict[make].append(model)

        # Sort makes and models
        sorted_dict = {}
        for make in sorted(vehicle_dict.keys()):
            sorted_dict[make] = sorted(vehicle_dict[make])

        # Write to JSON file
        print(f"Saving to {OUTPUT_FILE}...")
        with open(OUTPUT_FILE, 'w') as f:
            json.dump(sorted_dict, f, indent=4)
            
        print("Success! Data saved.")

    except Exception as e:
        print(f"An error occurred: {e}")
        sys.exit(1)

if __name__ == "__main__":
    # Ensure public directory exists
    os.makedirs('public', exist_ok=True)
    main()

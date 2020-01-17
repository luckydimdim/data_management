import csv
from datetime import date

result_rows = []

def calculate_age(birth_year):
    '''
    Calculates age from year of birth
    '''

    today = date.today()
    return today.year - birth_year


# Parse file
with open('question1a_append.csv', mode='r') as infile:
    reader = csv.DictReader(infile)

    for row in reader:
        sex = 'M' if row['D100'] == 1 else 'F'
        age = calculate_age(int(row['D101']))

        row['Match'] = sex == row['SEX'] and age == row['AGE']
        result_rows.append(row)

# Save results to file
with open('question1b_match.csv', mode='w') as file:
    writer = csv.DictWriter(file, fieldnames=result_rows[0].keys())

    writer.writeheader()

    for row in result_rows:
        writer.writerow(row)

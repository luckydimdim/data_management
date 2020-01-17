import csv

# names of additional column
cols = {
    'AGE': 'int', 'SEX': 'str', 'CF': 'int', 'REGNUM': 'int', 'BEST_PHONE': 'str',
    'TSCORE': 'int', 'PSCORE': 'int', 'G2016': 'str', 'DENSITY': 'int',
    'COUNTY': 'str', 'AGENTNUM': 'int'}

with open('question1_data.csv', mode='r') as infile:
    reader = csv.reader(infile)

    with open('question1a_append.csv', mode='w') as file:
        writer = csv.writer(file, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)

        row_index = 0

        # iterate rows
        for row in reader:
            last_col = row[len(row) - 1]
            del row[-1]

            if row_index == 0:
                row.extend(cols.keys())
            else:
                # evaluate cols
                data = last_col.split(';')

                for col in data:
                    cell_data = col.split('=')
                    cell_name = cell_data[0]
                    cell_value = cell_data[1]
                    cell_type = cols[cell_name]

                    # set -1 in case of wrong cell type
                    if cell_type == 'int' and cell_value.isdigit() == False:
                        cell_value = -1

                    row.append(cell_value)

            row_index += 1
            writer.writerow(row)
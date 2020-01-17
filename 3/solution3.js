const csv = require('csv-parser');
const createCsvWriter = require('csv-writer').createObjectCsvWriter;
const fs = require('fs');

const data = [];

// Load data from file
fs.createReadStream('question3_data.csv')
    .pipe(csv())
    .on('data', row => data.push(row))
    .on('end', () => {
        // Sort dataset so that entries with same source_id:
        // greater scores are at the top
        data.sort(
           function(a, b) {
              if (a.source_id === b.source_id) {
                 return a.score > b.score ? 1 : -1;

              }
              return a.source_id > b.source_id ? 1 : -1;
           });

        filterData(data);
    });

/**
 * Filter people by required criteria
 */
function filterData(data) {
    const resultRows = [];
    let previousId = 0;

    for (let row of data) {

        // Take only unique source_id with highest score
        if (row['source_id'] == previousId) {
            continue;
        }

        // Take only people who have scores
        if (!row['score']) {
            continue;
        }

        // Sorry, I didn't undarstand the requirement:
        // b. The match score must be at least 0.1 greater
        // than the next highest match for that person.

        // Score must be greater then 0.7
        if (row['score'] <= 0.7) {
            continue;
        }

        resultRows.push(row);
        previousId = row['source_id']
    }

    saveData(resultRows);
}

/**
 * Save matched people
 */
function saveData(data) {
    const csvWriter = createCsvWriter({
      path: 'question3_processed.csv',
      header: [
        {id: 'source_id', title: 'source_id'},
        {id: 'target_id', title: 'target_id'},
        {id: 'score', title: 'score'}
      ]
    });

    csvWriter.writeRecords(data)
      .then(()=> console.log('The CSV file was written successfully'));
}
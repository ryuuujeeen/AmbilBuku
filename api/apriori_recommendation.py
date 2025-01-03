import sys
import json
from efficient_apriori import apriori

# Debugging log
def debug_log(message):
    with open("debug.log", "a") as log_file:
        log_file.write(f"{message}\n")

try:
    # Step 1: Load transaction data
    with open("transactions.json", "r") as file:
        transactions = json.load(file)
    debug_log(f"Loaded {len(transactions)} transactions.")

    # Step 2: Get input ISBN
    input_isbn = sys.argv[1].strip()
    debug_log(f"Input ISBN: {input_isbn}")

    # Step 3: Run Apriori algorithm with adjusted thresholds
    min_support = 0.012 
    min_confidence = 0.6 
    itemsets, rules = apriori(transactions, min_support=min_support, min_confidence=min_confidence)
    debug_log(f"Found {len(rules)} rules.")

    # Step 4: Extract recommendations
    recommendations = []
    for rule in rules:
        debug_log(f"Rule: If {rule.lhs} then {rule.rhs} (Support: {rule.support}, Confidence: {rule.confidence})")
        if input_isbn in rule.lhs:
            recommendations.extend(rule.rhs)
    recommendations = list(set(recommendations))
    debug_log(f"Recommendations: {recommendations}")

    # Step 5: Output recommendations
    print(json.dumps(recommendations))
except Exception as e:
    debug_log(f"Error: {str(e)}")
    print(json.dumps([]))

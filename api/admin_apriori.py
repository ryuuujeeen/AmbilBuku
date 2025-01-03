import json
from efficient_apriori import apriori

# Debugging log
def debug_log(message):
    with open("debug2.log", "a") as log_file:
        log_file.write(f"{message}\n")

try:
    # Step 1: Load transaction data
    with open("transactions2.json", "r") as file:
        transactions = json.load(file)
    debug_log(f"Loaded {len(transactions)} transactions.")

    # Step 2: Run Apriori algorithm with adjusted thresholds
    min_support = 0.012
    min_confidence = 0.6
    itemsets, rules = apriori(transactions, min_support=min_support, min_confidence=min_confidence)
    debug_log(f"Found {len(rules)} rules.")

    # Step 3: Extract all association rules
    output_rules = []  # Indentation issue fixed here
    for rule in rules:
        debug_log(f"Rule: If {rule.lhs} then {rule.rhs} (Support: {rule.support}, Confidence: {rule.confidence})")
        output_rules.append({
            'lhs': list(rule.lhs),  # Keep LHS as a list
            'rhs': list(rule.rhs),  # Keep RHS as a list
            'support': round(rule.support, 3),  # Round support to 3 decimal places
            'confidence': round(rule.confidence, 3)  # Round confidence to 3 decimal places
        })

    # Step 4: Output rules
    debug_log(f"Generated rules: {output_rules}")
    print(json.dumps(output_rules))
except Exception as e:
    debug_log(f"Error: {str(e)}")
    print(json.dumps([]))

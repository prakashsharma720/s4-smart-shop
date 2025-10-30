-- Example Query: Get a User’s Downline

SELECT u.* 
FROM users u 
JOIN user_tree t ON u.id = t.child_id 
WHERE t.parent_id = :user_id;

-- Bonus Tip

-- You can manage this tree easily using recursive queries (CTE) in MySQL 8+:

WITH RECURSIVE downline AS (
  SELECT id, user_id, name, 0 AS level
  FROM users
  WHERE user_id = 'S40001'
  
  UNION ALL
  
  SELECT u.id, u.user_id, u.name, d.level + 1
  FROM users u
  INNER JOIN user_tree t ON t.child_id = u.id
  INNER JOIN downline d ON d.id = t.parent_id
)
SELECT * FROM downline;

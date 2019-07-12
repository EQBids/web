
SELECT u.email , u.first_name, u.last_name, count(*) FROM bids b 
inner join orders o on o.id = b.order_id
inner join users u on u.id = o.user_id
where b.supplier_id = 18
group by o.user_id